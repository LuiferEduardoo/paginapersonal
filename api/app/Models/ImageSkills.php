<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageSkills extends Model
{
    protected $table = 'image_skills';
    protected $fillable = ['image_id', 'skill_id'];
}