<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Parsedown;
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
    protected $parsedown;
    protected $token;
    protected $repositoryUrl; 
    protected $haveImages;     
    protected $images;  
    protected $ids_images;
    protected $categories;
    protected $subcategories;
    protected $tags;
    protected $tecnhologies;
    protected $visible;
    protected $replaceImages;
    protected $eliminateImages;

    public function __construct(ClassificationService $classificationService, ImageAssociationService $imageAssociationService, GithubService $githubService, TechnologyService $technologyService, Request $request)
    {
        $this->classificationService = $classificationService;
        $this->imageAssociationService = $imageAssociationService;
        $this->githubService = $githubService;
        $this->technologyService = $technologyService;
        $this->parsedown = new Parsedown();
        $this->token = str_replace('Bearer ', '', $request->header('Authorization'));
        $this->haveImages = $request->hasFile('images') ? $request->hasFile('images') : $request->hasFile('image');
        $this->images = $request->file('images') ? $request->file('images') : $request->file('image');
        $this->ids_images = $request->input('ids_images') ? $request->input('ids_images') : $request->input('id_image');
        $this->categories = explode(",",$request->input('categories'));
        $this->subcategories = explode(",",$request->input('subcategories'));
        $this->tags = explode(",",$request->input('tags'));
        $this->tecnhologies = explode(",",$request->input('technologies'));
        $this->visible = filter_var($request->input('visible'), FILTER_VALIDATE_BOOLEAN);
        $this->repositoryUrl = $request->input('url_repository');
        $this->replaceImages = $request->input('replace_image')? filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN) : filter_var($request->input('replace_images'), FILTER_VALIDATE_BOOLEAN) ;
        $this->eliminateImages = $request->input('eliminate_image') ? filter_var($request->input('eliminate_image'), FILTER_VALIDATE_BOOLEAN) :  $request->input('eliminate_images');
    }
    protected function executeInTransaction($callback)
    {
        try {
            DB::beginTransaction();
            $result = $callback();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    protected function saveImagesAndClassification($object, $folder, $association, $isProject=false, $haveMiniature=null, $miniature=null, $idMiniature=null){
        $this->imageAssociationService->saveImages($object, $this->haveImages, $this->images, $this->ids_images, $folder, $association, $this->token); // Se guarda la imagen 
        $this->classificationService->createItems($object, $this->tags, 'tags', Tags::class, 'name');
        $this->classificationService->createItems($object, $this->categories, 'categories', Categories::class, 'name');
        $this->classificationService->createItems($object, $this->subcategories, 'subcategories', Subcategories::class, 'name');
        if($isProject){
            $this->technologyService->addTechnology($object, $this->tecnhologies);
            $this->githubService->getInformationRepository($object, $this->repositoryUrl); // Se obtiene la información de repositorio en github
            $this->imageAssociationService->saveImages($object, $haveMiniature, $miniature, $idMiniature, 'project/miniature', 'miniature', $this->token); // Se guarda la miniatura
        }
    }
    protected function deleteImagesAndClassification($object, $relationImages, $technologyService=null, $eliminateMiniature=false){
        $this->imageAssociationService->deleteImages($object, $relationImages, $this->eliminateImages, $this->token);
        $itemsClassificationOfEliminate = array('tags', 'categories', 'subcategories');
        foreach($itemsClassificationOfEliminate as $item){
            if($item != null){
                $this->classificationService->deleteItems($object, $item);
            }
            if($technologyService != null){
                $this->technologyService->deleteTechnology($object); // Se Borran las tecnologias
                $this->imageAssociationService->deleteImages($object, 'miniature', $eliminateMiniature, $this->token); // Se borra la miniatura
                $this->githubService->deleteAllRelations($object); // Se borran todas las relaciones de la información de github
            }
        }
    }
    protected function updateImagesAndClassification($object, $relation, $folder, $haveMiniature=false, $miniature=null, $idMiniature=null, $replaceMiniature=false){
        if($this->haveImages || $this->ids_images){
            $this->imageAssociationService->updateImages($object,  $this->haveImages, $this->images, $this->replaceImages, $relation,  $this->ids_images, $folder, $this->token);
        }
        if($this->tags[0]){
            $this->classificationService->updateItems($object, $this->tags, 'tags', Tags::class, 'name');
        }
        if($this->categories[0]){
            $this->classificationService->updateItems($object, $this->categories, 'categories', Categories::class, 'name');
        }
        if($this->subcategories[0]){
            $this->classificationService->updateItems($object, $this->subcategories, 'subcategories', Subcategories::class, 'name');
        }
        if($this->tecnhologies[0]){
            $this->technologyService->updateTechnology($object, $this->tecnhologies);
        }
        if($this->repositoryUrl !== null){
            $this->githubService->getInformationRepository($object, $this->repositoryUrl);
        }
        if($haveMiniature){
            $this->imageAssociationService->updateImages($object, $haveMiniature, $miniature, $replaceMiniature, 'miniature', $idMiniature, 'project/miniature', $this->token);
        }
    }
    protected function readingTime($content){
        $numberWords = str_word_count(strip_tags($content));
        $readingTimeNoForm = ceil($numberWords / 200);
        $readingTimeHours = floor($readingTimeNoForm/60); 
        $readingTimeMinutes = $readingTimeNoForm % 60;
        return gmdate('H:i:s', mktime($readingTimeHours, $readingTimeMinutes, 0, 0, 0, 0));
    }


    protected function link($title, $object){
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