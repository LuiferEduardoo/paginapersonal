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
            $this->saveImagesAndClassification($blogPost, 'blog', 'image');
            return response()->json([
                'message' => 'Blog post successfully created'
            ], 200);
        });
    }

    public function deleteBlogPost(Request $request){
        return $this->executeInTransaction(function () use ($request) {
            $id = $request->input('id');
            if(BlogPost::findOrFail($id)){
                $blogPost = BlogPost::findOrFail($id);
                $this->deleteImagesAndClassification($blogPost, 'image');
                $blogPost->delete();
                return response()->json(['message' => 'Blog post successfully deleted'],200);
            }
            return response()->json([
                'message' => "Blog post not fount"
            ], 404);
        });
    }

    public function putBlogPost(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $blogPost = BlogPost::findOrFail($id);
            $title = $request->input('title');
            $content = $this->parsedown->text($request->input('content'));
            $blogPost->title = $title;
            $blogPost->content = $content;
            $blogPost->link = $this->link->generate($title, $blogPost);
            $blogPost->authors = $this->authors($request);
            $blogPost->reading_time = $this->time->readingTime($request->input('content'));
            $blogPost->image_credits = $request->input('image_credits');
            $blogPost->save();
            $this->updateImagesAndClassification($blogPost, 'image', 'blog'); // Guardamos las imagenes y clasificaciones
            return response()->json([
                'message' => 'Blog post successfully updated'
            ], 200);
        });
    }

    public function patchBlogPost(ValidateDate $request, $id){
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
            $this->updateImagesAndClassification($blogPost, 'image', 'blog'); // Guardamos las imagenes y clasificaciones
            $blogPost->save();
            return response()->json([
                'message' => 'Blog post updated successfully',
            ], 200);
        });
    }
}