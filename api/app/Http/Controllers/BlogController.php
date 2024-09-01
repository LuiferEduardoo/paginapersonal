<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateDate;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;

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
        return $this->executeInTransaction(function () use ($request) {
            // Crea la habilidad
            $blogPost = new BlogPost([
                'title' => $request->input('title'),
                'content' => $this->parsedown->text($request->input('content')),
                'link' => $this->link->generate($request->input('title'), BlogPost::class),
                'authors' => $this->authors($request),
                'reading_time' => $this->time->readingTime($request->input('content')),
                'image_credits' => $request->input('image_credits'),
            ]);
            $blogPost->user_id = $request->user()->id;
            $blogPost->save();
            $this->imageAssociationService->saveImages($blogPost, $this->haveImages, $this->images, $this->ids_images, 'blog', 'image', $this->token);
            $this->saveClassification($blogPost);
            return response()->json([
                'message' => 'Blog post successfully created'
            ], 200);
        });
    }

    public function deleteBlogPost(Request $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $blogPost = $this->HandlesFilndElement->findOne(BlogPost::class, $id);
            if($blogPost){
                $this->imageAssociationService->deleteImages($blogPost, 'image', $this->eliminateImages, $this->token);
                $this->deleteClassification($blogPost);
                $blogPost->delete();
                return response()->json(['message' => 'Blog post successfully deleted'],200);
            }
        });
    }


    public function updateBlogPost(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $blogPost = BlogPost::find($id);
            if ($request->input('title')) {
                $title = $request->input('title');
                $blogPost->title = $title;
                $blogPost->link = $this->link->generate($title, $blogPost);
            }
            if($request->input('content')){
                $content = $request->input('content');
                $blogPost->content = $this->parsedown->text($content);
                $blogPost->reading_time = $this->time->readingTime($content);
            }
            if($request->input('authors')){
                $blogPost->authors = $this->authors($request);
            }
            if($request->input('image_credits')){
                $blogPost->image_credits = $request->input('image_credits');
            }
            if($this->haveImages || $this->ids_images){
                $this->imageAssociationService->updateImages($blogPost,  $this->haveImages, $this->images, $this->replaceImages, 'image',  $this->ids_images, 'blog', $this->token);
            }
            $this->updateClassification($blogPost); // Guardamos las imagenes y clasificaciones
            $blogPost->save();
            return response()->json([
                'message' => 'Blog post updated successfully',
            ], 200);
        });
    }
}