<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Parsedown;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Http\Requests\ValidateDate;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class BlogController extends Controller
{
    public function authors (Request $request){
        $authors = explode(",", $request->user()->name);
        $authorsInsert = $request->input('authors');
        if($authorsInsert){
            $authorsInsertArray = explode(",", $authorsInsert);
            $authors = array_merge($authors, $authorsInsertArray);
        }
        return $authors;
    }
    public function getBlogPost(Request $request){
        $query = BlogPost::with('user.profile', 'image', 'categories', 'subcategories' ,'tags');
        if ($request->input('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }
        $query->where('visible', true);
        $query->orderBy('created_at', 'desc');
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
            $blogPost = new BlogPost([
                'title' => $title,
                'content' => $content,
                'link' => $this->link($title),
                'authors' => $this->authors($request),
                'reading_time' => $this->readingTime($content),
                'image_credits' => $request->input('image_credits'),
            ]);
            $blogPost->user_id = $request->user()->id;
            $blogPost->save();

            $this->imageAssociationService->saveImages($blogpost, $request->hasFile('image'), $request->file('image'), $request->input('id_image'), 'blog', 'image', $token); // Se guarda la imagen 

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
            $eliminateImage =  filter_var($request->input('eliminate_image'), FILTER_VALIDATE_BOOLEAN);

            if(BlogPost::findOrFail($id)){
                $blogPost = BlogPost::findOrFail($id);
                $items = array('tags', 'categories', 'subcategories');
                foreach ($items as $item){
                    $this->classificationService->deleteItems($blogPost, $item);
                }
                $this->imageAssociationService->deleteImages($blogPost, 'image', $eliminateImage, $token);
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
            $blogPost->authors = $this->authors($request);
            $blogPost->reading_time = $this->readingTime($content);
            $blogPost->image_credits = $request->input('image_credits');
            $blogPost->save();
    
            // Se actualizan las categorias, las subcategorias y los tags
            $this->classificationService->updateItems($blogPost, explode(",", $request->input('categories')), 'categories', Categories::class, 'name');
            $this->classificationService->updateItems($blogPost, explode(",", $request->input('subcategories')), 'subcategories', Subcategories::class, 'name');
            $this->classificationService->updateItems($blogPost, explode(",", $request->input('tags')), 'tags', Tags::class, 'name');

            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);
            $this->imageAssociationService->updateImages($blogPost, $request->hasFile('image'), $request->file('image'), $replaceImage, 'image', $request->input('id_image'), 'blog', $token); // Se actualiza la imagen
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
                $blogPost->authors = $this->authors($request);
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

            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);
            $this->imageAssociationService->updateImages($blogPost, $request->hasFile('image'), $request->file('image'), $replaceImage, 'image', $request->input('id_image'), 'blog', $token); // Se actualiza la imagen
        
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