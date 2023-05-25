<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationOfImages extends Model
{
    use HasFactory;
    protected $table = 'registration_of_images';
    protected $fillable = ['name', 'folder', 'url'];

    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'image_skills', 'image_id', 'skill_id');
    }
}