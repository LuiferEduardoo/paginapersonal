<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function projects()
    {
        return $this->belongsToMany(Projects::class, 'subcategories_project', 'subcategory_id', 'project_id');
    }
}
