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
use App\utils\Link;
use App\utils\Time;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $link;
    protected $time;
    protected $classificationService;
    protected $imageAssociationService;
    protected $githubService;
    protected $technologyService;
    protected $parsedown;
    protected $token;
    protected $repositoriesUrl;
    protected $idsUpdateRepositories;
    protected $idsEliminateRepositories;
    protected $categoriesRepositories;
    protected $categoriesRepositoriesUpdate;
    protected $haveImages;     
    protected $images;  
    protected $ids_images;
    protected $categories;
    protected $subcategories;
    protected $tags;
    protected $tecnhologies;
    protected $visible;
    protected $important;
    protected $replaceImages;
    protected $eliminateImages;

    public function __construct(Link $link, Time $time, ClassificationService $classificationService, ImageAssociationService $imageAssociationService, GithubService $githubService, TechnologyService $technologyService, Request $request)
    {
        $this->link = $link;
        $this->time = $time;
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
        $this->important = filter_var($request->input('important'), FILTER_VALIDATE_BOOLEAN);
        $this->repositoriesUrl = $request->input('url_repositories') ? explode(",", $request->input('url_repositories')) : [];
        $this->idsUpdateRepositories = $request->input('ids_update_repositories') ? explode(",", $request->input('ids_update_repositories')) : [];
        $this->idsEliminateRepositories = $request->input('ids_eliminate_repositories') ? explode(",", $request->input('ids_eliminate_repositories')) : [];
        $this->categoriesRepositories = $request->input('categories_repositories') ? explode(",", $request->input('categories_repositories')) : [];
        $this->categoriesRepositoriesUpdate = $request->input('categories_repositories_update') ? explode(",", $request->input('categories_repositories_update')) : [];
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

    protected function saveClassification($object, $isProject=false){
        $this->classificationService->createItems($object, $this->tags, 'tags', Tags::class, 'name');
        $this->classificationService->createItems($object, $this->categories, 'categories', Categories::class, 'name');
        $this->classificationService->createItems($object, $this->subcategories, 'subcategories', Subcategories::class, 'name');
        if($isProject){
            $this->technologyService->addTechnology($object, $this->tecnhologies);
            $this->githubService->create($object, $this->repositoriesUrl, $this->categoriesRepositories); // Se obtiene la información de repositorio en github
        }
    }
    protected function deleteClassification($object, $technologyService=null){
        $itemsClassificationOfEliminate = array('tags', 'categories', 'subcategories');
        foreach($itemsClassificationOfEliminate as $item){
            if($item != null){
                $this->classificationService->deleteItems($object, $item);
            }
            if($technologyService != null){
                $this->technologyService->deleteTechnology($object); // Se Borran las tecnologias
                $this->githubService->delete($object); // Se borran todas las relaciones de la información de github
            }
        }
    }
    protected function updateClassification($object, $isProject=false){
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
        if($isProject){
            $this->githubService->update($object, $this->repositoriesUrl, $this->categoriesRepositories, $this->idsUpdateRepositories, $this->categoriesRepositoriesUpdate, $this->idsEliminateRepositories);
        }
    }
}