<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;

class RegistrationOfImages extends Model
{
    use HasFactory;
    protected $table = 'registration_of_images';
    protected $fillable = ['name', 'folder', 'url'];

    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'image_skills', 'image_id', 'skill_id');
    }

    public function project()
    {
        return $this->belongsToMany(Projects::class, 'image_projects', 'image_id', 'project_id');
    }

    public function projectMiniature()
    {
        return $this->belongsToMany(Projects::class, 'miniature_projects', 'image_id', 'project_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_profiles', 'image_id', 'user_id');
    }

}