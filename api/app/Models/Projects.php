<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'id_repository', 'link', 'brief_description', 'description', 'date', 'updated', 'version', 'url_proyect', 'documentation', 'contributors'];

    protected $casts = [
        'contributors' => 'json'
    ];

    public function image(){
        return $this->belongsToMany(RegistrationOfImages::class, 'image_projects', 'project_id', 'image_id');
    }

    public function miniature(){
        return $this->belongsToMany(RegistrationOfImages::class, 'miniature_projects', 'project_id', 'image_id' );
    }

    public function technology(){
        return $this->belongsToMany(Skills::class, 'technologies_projects', 'projects_id', 'technology_id');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, 'categories_project', 'project_id', 'category_id');
    }

    public function subcategories(){
        return $this->belongsToMany(Subcategories::class, 'subcategories_project', 'project_id', 'subcategory_id');
    }

    public function tags(){
        return $this->belongsToMany(Tags::class, 'tags_projects', 'projects_id', 'tag_id');
    }
}
