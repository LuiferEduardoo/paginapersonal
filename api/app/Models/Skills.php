<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url', 'date'];

    public function GetTags()
    {
        return $this->hasManyThrough(Tags::class, TagsSkills::class, 'id_skills', 'id', 'id', 'id_tag');
    }

    public function image()
    {
        return $this->hasManyThrough(RegistrationOfImages::class, ImageSkills::class, 'skill_id', 'id', 'id', 'image_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'tags_skills', 'id_skills', 'id_tag');
    }


    public function RelationTags(){
        return $this->belongsToMany(Tags::class, 'tags_skills', 'id_skills', 'id_tag');
    }

    public function RelationImage(){
        return $this->belongsToMany(RegistrationOfImages::class, 'image_skills', 'skill_id', 'image_id');
    }
}
