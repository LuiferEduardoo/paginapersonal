<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url', 'date'];

    public function image()
    {
        return $this->belongsToMany(RegistrationOfImages::class, 'image_skills', 'skill_id', 'image_id');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, 'categories_skill', 'skill_id', 'category_id');
    }

    public function subcategories(){
        return $this->belongsToMany(Subcategories::class, 'subcategories_skill', 'skill_id', 'subcategory_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'tags_skills', 'id_skills', 'id_tag');
    }
    
    public function project(){
        return $this->belongsToMany(Projects::class, 'technologies_projects', 'projects_id', 'technology_id');
    }
}
