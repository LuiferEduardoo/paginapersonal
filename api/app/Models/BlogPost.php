<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_post';
    protected $fillable = ['title', 'content', 'date', 'link', 'authors', 'reading_time','image_credits'];


    public function image(){
        return $this->belongsToMany(RegistrationOfImages::class, 'image_projects', 'image_id', 'project_id');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, 'categories_project', 'category_id', 'project_id');
    }

    public function subcategories(){
        return $this->belongsToMany(Subcategories::class, 'subcategories_project', 'subcategory_id', 'project_id');
    }

    public function tags(){
        return $this->belongsToMany(Tags::class, 'tags_projects', 'id_project', 'id_tag');
    }
}
