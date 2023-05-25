<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagsSkills extends Model
{
    protected $table = 'tags_skills';
    protected $fillable = ['id_skills', 'id_tag'];
}
