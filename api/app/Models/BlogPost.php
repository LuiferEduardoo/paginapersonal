<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_post';
    protected $fillable = ['title', 'content', 'link', 'authors', 'reading_time','image_credits'];

    protected $casts = [
        'authors' => 'json'
    ];
    public function image(){
        return $this->belongsToMany(RegistrationOfImages::class, 'image_blog', 'blog_post_id', 'image_id');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, 'categories_blog', 'blog_post_id', 'category_id');
    }

    public function subcategories(){
        return $this->belongsToMany(Subcategories::class, 'subcategories_blog', 'blog_post_id', 'subcategory_id');
    }

    public function tags(){
        return $this->belongsToMany(Tags::class, 'tags_blog', 'blog_post_id', 'tag_id');
    }
}
