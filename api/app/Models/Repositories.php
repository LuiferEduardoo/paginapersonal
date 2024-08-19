<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repositories extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'link'];

    public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, 'categories_repositories', 'repository_id', 'category_id');
    }
}