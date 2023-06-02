<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projects(){
        return $this->belongsToMany(Projects::class, 'categories_project', 'category_id	', 'project_id');
    }

    public function blog(){
        return $this->belongsToMany(BlogPost::class, 'categories_blog', 'blog_post_id	', 'category_id');
    }
}
