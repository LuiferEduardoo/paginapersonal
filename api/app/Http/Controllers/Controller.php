<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Services\ImageAssociationService;
use App\Services\ClassificationService;
use App\Services\GithubService;
use App\Services\TechnologyService;
use App\Http\Requests\ValidateDate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $classificationService;
    protected $imageAssociationService;
    protected $githubService;
    protected $technologyService;

    public function __construct(ClassificationService $classificationService, ImageAssociationService $imageAssociationService, GithubService $githubService, TechnologyService $technologyService)
    {
        $this->classificationService = $classificationService;
        $this->imageAssociationService = $imageAssociationService;
        $this->githubService = $githubService;
        $this->technologyService = $technologyService;
    }
    
    public function readingTime($content){
        $numberWords = str_word_count(strip_tags($content));
        $readingTimeNoForm = ceil($numberWords / 200);
        $readingTimeHours = floor($readingTimeNoForm/60); 
        $readingTimeMinutes = $readingTimeNoForm % 60;
        return gmdate('H:i:s', mktime($readingTimeHours, $readingTimeMinutes, 0, 0, 0, 0));
    }


    public function link($title, $object){
        // Eliminar caracteres especiales y conservar tildes
        $link = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $title));
        $link = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $link));

        $baseLink = $link;
        $suffix = 1;
        while ($object::where('link', $link)->exists()) {
            $link = "$baseLink-$suffix";
            $suffix++;
        }
        return $link;
    }
}
