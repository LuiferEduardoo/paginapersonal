<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Parsedown;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Services\ImageAssociationService;
use App\Services\ClassificationService;
use App\Http\Requests\ValidateDate;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class BlogController extends Controller
{
    protected $classificationService;
    protected $imageAssociationService;

    public function __construct(ClassificationService $classificationService, ImageAssociationService $imageAssociationService)
    {
        $this->classificationService = $classificationService;
        $this->imageAssociationService = $imageAssociationService;
    }
    public function link($title){
        // Eliminar caracteres especiales y conservar tildes
        $link = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $title));
        $link = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $link));

        $baseLink = $link;
        $suffix = 1;
        while (BlogPost::where('link', $link)->exists()) {
            $link = "$baseLink-$suffix";
            $suffix++;
        }
        return $link;
    }
    public function readingTime($content){
        $numberWords = str_word_count(strip_tags($content));
        $readingTimeNoForm = ceil($numberWords / 200);
        $readingTimeHours = floor($readingTimeNoForm/60); 
        $readingTimeMinutes = $readingTimeNoForm % 60;
        return gmdate('H:i:s', mktime($readingTimeHours, $readingTimeMinutes, 0, 0, 0, 0));
    }
    public function getBlogPost(Request $request){
        $query = BlogPost::with('image', 'categories', 'subcategories' ,'tags');
        if ($request->input('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }
        $query->where('visible', true);
        $blogPost = $query->get();
        return response()->json($blogPost);
    }
    public function createBlogPost(ValidateDate $request){
        try{
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            DB::beginTransaction();
            $parsedown = new Parsedown();
            $title = $request->input('title');
            $content = $parsedown->text($request->input('content'));
            $blogPost = BlogPost::create([
                'title' => $title,
                'content' => $content,
                'link' => $this->link($title),
                'authors' => explode(",", $request->input('authors')),
                'reading_time' => $this->readingTime($content),
                'image_credits' => $request->input('image_credits'),
            ]);

            if($request->input('id_image')){
                $this->imageAssociationService->saveImageForId($blogPost, $request->input('id_image'), 'image');
            } else if($request->hasFile('image')){
                $this->imageAssociationService->saveImage($blogPost, $request->file('image'), 'blog', 'image', $token);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Miniature not entered"
                    ], 400);
            }
            $this->classificationService->createItems($blogPost, explode(",", $request->input('categories')), 'categories', Categories::class, 'name');
            $this->classificationService->createItems($blogPost, explode(",", $request->input('subcategories')), 'subcategories', Subcategories::class, 'name');
            if($request->input('tags')){
                $this->classificationService->createItems($blogPost, explode(",", $request->input('tags')), 'tags', Tags::class, 'name');
            }
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Blog post successfully created'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Blog post not fount"
            ], 404);
        }
    }

    public function deleteBlogPost(Request $request){
        try{
            $id = $request->input('id');

            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $eliminateImage = filter_var($request->input('eliminate_image'), FILTER_VALIDATE_BOOLEAN);

            if(BlogPost::findOrFail($id)){
                $blogPost = BlogPost::findOrFail($id);
                $items = array('tags', 'categories', 'subcategories');
                foreach ($items as $item){
                    $this->classificationService->deleteItems($blogPost, $item);
                }
                $this->imageAssociationService->deleteImage($blogPost, 'image', $eliminateImage, $token);
                $blogPost->delete();
                return response()->json(['message' => 'Blog post successfully deleted'],200);
            }
            return response()->json([
                'message' => "Blog post not fount"
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            return response()->json([
                'message' => "Error removing blog post"
            ], 500);
        }
    }

    public function putBlogPost(ValidateDate $request, $id){
        try{
            DB::beginTransaction();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $blogPost = BlogPost::findOrFail($id);
            $parsedown = new Parsedown();
            $title = $request->input('title');
            $content = $parsedown->text($request->input('content'));
            $blogPost->title = $title;
            $blogPost->content = $content;
            $blogPost->link = $this->link($title);
            $blogPost->authors = explode(",", $request->input('authors'));
            $blogPost->reading_time = $this->readingTime($content);
            $blogPost->image_credits = $request->input('image_credits');
            $blogPost->save();
    
            // Se actualizan las categorias, las subcategorias y los tags
            $this->classificationService->updateItems($blogPost, explode(",", $request->input('categories')), 'categories', Categories::class, 'name');
            $this->classificationService->updateItems($blogPost, explode(",", $request->input('subcategories')), 'subcategories', Subcategories::class, 'name');
            $this->classificationService->updateItems($blogPost, explode(",", $request->input('tags')), 'tags', Tags::class, 'name');

            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);
            if($request->input('id_image')){
                $this->imageAssociationService->updateImageForId($blogPost, $request->input('id_image'), $replaceImage, 'image', $token);
            } else if($request->hasFile('image')){
                $image = $request->file('image');
                $this->imageAssociationService->updateImage($blogPost, $image, $replaceImage, 'image', 'project/image', $token);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Blog post successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Blog post not fount"
            ], 404);
        }
    }

    public function patchBlogPost(ValidateDate $request, $id){
        $errorImage = response()->json([
            'message' => "You cannot upload more than one image"
        ], 409);
        try{
            DB::beginTransaction();
            $parsedown = new Parsedown();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $blogPost = BlogPost::find($id);
            $replaceImage = $request->input('replace_image');
        
            if ($request->input('title')) {
                $title = $request->input('title');
                $blogPost->title = $title;
                $blogPost->link = $this->link($title);
            }
            if($request->input('content')){
                $content = $parsedown->text($request->input('content'));
                $blogPost->content = $content;
                $blogPost->reading_time = $this->readingTime($content);
            }
            if($request->input('authors')){
                $blogPost->authors = explode(",", $request->input('authors'));
            }
            if($request->input('image_credits')){
                $blogPost->image_credits = $request->input('image_credits');
            }
            if($request->input('categories')){
                $this->classificationService->updateItems($blogPost, explode(",", $request->input('categories')), 'categories', Categories::class, 'name');
            }
            if($request->input('subcategories')){
                $this->classificationService->updateItems($blogPost, explode(",", $request->input('subcategories')), 'subcategories', Subcategories::class, 'name');
            }
            if($request->input('tags')){
                $this->classificationService->updateItems($blogPost, explode(",", $request->input('tags')), 'tags', Tags::class, 'name');
            }

            $replaceImage = filter_var($request->input('replace_images'), FILTER_VALIDATE_BOOLEAN);

            if($request->file('image')){
                $image = $request->file('image'); 
                if(!$request->input('id_image')){
                    $this->imageAssociationService->updateImage($blogPost, $image, $replaceImage, 'image', 'project/image', $token);
                } else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('id_image')){
                $idImage = $request->input('id_image');
                if(!$request->file('image')){
                    $this->imageAssociationService->updateImageForId($blogPost, $idImage, $replaceImage, 'image', $token);
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
        
            $blogPost->save();
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Blog post updated successfully',
            ], 200);
        }catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Blog post not fount"
            ], 404);
        }
    }
}