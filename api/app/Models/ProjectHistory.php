<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHistory extends Model
{
    use HasFactory;

    protected $fillable = ['id_project', 'id_repository', 'description', 'date', 'updated', 'pushed_at', 'version', 'url_proyect', 'url_repository', 'documentation', 'contributors'];

    public function project()
    {
        return $this->belongsTo(Projects::class, 'id_project');
    }
}
