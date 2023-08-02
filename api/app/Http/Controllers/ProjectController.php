<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Services\ImageAssociationService;
use App\Services\GithubService;
use App\Services\ClassificationService;
use App\Services\TechnologyService;
use App\Http\Requests\ValidateDate;
use App\Models\Projects;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProjectController extends Controller
{
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

    public function link($title){
        // Eliminar caracteres especiales y conservar tildes
        $link = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $title));
        $link = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $link));

        $baseLink = $link;
        $suffix = 1;
        while (Projects::where('link', $link)->exists()) {
            $link = "$baseLink-$suffix";
            $suffix++;
        }
        return $link;
    }
    public function getProject(Request $request){
        $query = Projects::with('miniature', 'image', 'categories', 'subcategories', 'technology' ,'tags');
        if ($request->input('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }
        $query->where('visible', true);
        $query->with(['history' => function ($historyQuery) {
            $historyQuery->latest('created_at');
        }]);
        $query->orderBy('created_at', 'desc');
        $project = $query->get();
        return response()->json($project);
    }

    public function postProject(ValidateDate $request){
        try{
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $categories = $request->input('categories');
            $subcategories = $request->input('subcategories');
            $technologies = $request->input('technologies');
            $tags = $request->input('tags');
            $repositoryUrl = $request->input('url_repository');
            DB::beginTransaction();
            // Crea el proyecto
            $project = new Projects([
                'name' => $request->input('name'),
                'brief_description' => $request->input('brief_description'),
                'link' => $this->link($request->input('name')),
                ]);
            $project->save();
            // Se guarda la información del repositorio en la base de datos
            $this->githubService->getInformationRepository($project, $repositoryUrl);
            if($request->input('id_miniature')){
                $miniaturaId = $request->input('id_miniature');
                $this->imageAssociationService->saveImageForId($project, $miniaturaId, 'miniature');
            } else if($request->hasFile('miniature')){
                $miniatura = $request->file('miniature');
                $this->imageAssociationService->saveImage($project, $miniatura, 'project/miniature', 'miniature', $token);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Miniature not entered"
                    ], 400);
            }

            if($request->input('ids_images')){
                $ids =  $arrayTags = explode(",", $request->input('ids_images'));
                foreach ($ids as $id) {
                    $this->imageAssociationService->saveImageForId($project, $id, 'image');
                }
            } else if($request->hasFile('images')){
                $images = $request->file('images');
                foreach ($images as $image){
                    $this->imageAssociationService->saveImage($project, $image, 'project/image', 'image', $token);
                }
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            $this->technologyService->addTechnology($project, explode(",", $technologies));
            $this->classificationService->createItems($project, explode(",", $categories), 'categories', Categories::class, 'name');
            $this->classificationService->createItems($project, explode(",", $subcategories), 'subcategories', Subcategories::class, 'name');
            $this->classificationService->createItems($project, explode(",", $tags), 'tags', Tags::class, 'name');

            $project->save();
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Project successfully created'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Project not fount"
            ], 404);
        }
    }

    public function deleteProject(Request $request){
        try{
            $id = $request->input('id');

            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $eliminateImages = $request->input('eliminate_images');
            $eliminateMiniature = $request->input('eliminate_miniature');

            if(Projects::findOrFail($id)){
                $project = Projects::findOrFail($id);
                $items = array('tags', 'categories', 'subcategories');
                foreach ($items as $item){
                    $this->classificationService->deleteItems($project, $item);
                }
                $this->githubService->deleteAllRelations($project);
                
                $this->technologyService->deleteTechnology($project);
                $this->imageAssociationService->deleteImage($project, 'image', $eliminateImages, $token);
                $this->imageAssociationService->deleteImage($project, 'miniature', $eliminateMiniature, $token);
                $project->delete();
                return response()->json(['message' => 'Project successfully deleted'],200);
            }
            return response()->json([
                'message' => "Project not fount"
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            return response()->json([
                'message' => "Error removing project"
            ], 500);
        }
    }

    public function putProject(ValidateDate $request, $id){
        try{
            DB::beginTransaction();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
    
            $urlRepository = $request->input('url_repository');
    
            $project = Projects::findOrFail($id);
    
            // Recuperar los nuevos datos de la habilidad desde el request
            $categories = $request->input('categories');
            $subcategories = $request->input('subcategories');
            $technologies = $request->input('technologies');
            $tags = $request->input('tags');
    
            // Se actualiza los campos de projects
            $project->name = $request->input('name');
            $project->link = $this->link($request->input('name'));
            $project->brief_description = $request->input('brief_description');
            $project->url_repository = $urlRepository;
            
            $project->save();
            $this->githubService->getInformationRepository($project, $urlRepository);
    
            // Se actualizan las categorias, las subcategorias, las tecnologias y los tags
            $this->technologyService->updateTechnology($project, explode(",", $technologies));
            $this->classificationService->updateItems($project, explode(",", $categories), 'categories', Categories::class, 'name');
            $this->classificationService->updateItems($project, explode(",", $subcategories), 'subcategories', Subcategories::class, 'name');
            $this->classificationService->updateItems($project, explode(",", $tags), 'tags', Tags::class, 'name');
            
            $replaceMiniature = filter_var($request->input('replace_miniature'), FILTER_VALIDATE_BOOLEAN);
    
            if($request->input('id_miniature')){
                $miniaturaId = $request->input('id_miniature');
                $this->imageAssociationService->updateImageForId($project, $miniaturaId, $replaceMiniature, 'miniature', $token);
            } else if($request->hasFile('miniature')){
                $miniatura = $request->file('miniature');
                $this->imageAssociationService->updateImage($project, $miniatura, $replaceMiniature, 'miniature', 'project/miniature', $token);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Miniature not entered"
                    ], 400);
            }
    
            $replaceImages = filter_var($request->input('replace_images'), FILTER_VALIDATE_BOOLEAN);
            if($request->input('ids_images')){
                $ids = explode(",", $request->input('ids_images'));
                foreach ($ids as $id) {
                    $this->imageAssociationService->updateImageForId($project, $id, $replaceImages, 'image', $token);
                }
            } else if($request->hasFile('images')){
                $images = $request->file('images');
                foreach ($images as $image){
                    $this->imageAssociationService->updateImage($project, $image, $replaceImages, 'image', 'project/image', $token);
                }
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Project successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Project not fount"
            ], 404);
        }
    }

    public function patchProject(ValidateDate $request, $id){
        $errorImage = response()->json([
            'message' => "You cannot upload more than one image"
        ], 409);
        try{
            DB::beginTransaction();

            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $project = Projects::find($id);
            $replaceImage = $request->input('replace_image');
        
            if ($request->input('name')) {
                $name = $request->input('name');
                $project->name = $name;
                $proyect->link = $this->link($name);
            }
            if ($request->input('url_repository')) {
                $urlRepository = $request->input('url_repository');
                $this->githubService->getInformationRepository($project, $urlRepository);
            }
            if ($request->input('brief_description')) {
                $briefDescription = $request->input('brief_description');
                $project->brief_description = $briefDescription;
            }
            if ($request->input('visible') !== null) {
                $visible = filter_var($request->input('visible'), FILTER_VALIDATE_BOOLEAN);
                $project->visible = $visible;
            }
            if($request->input('categories')){
                $categories = $request->input('categories');
                $this->classificationService->updateItems($project, explode(",", $categories), 'categories', Categories::class, 'name');
            }
            if($request->input('subcategories')){
                $subcategories = $request->input('subcategories');
                $this->classificationService->updateItems($project, explode(",", $subcategories), 'subcategories', Subcategories::class, 'name');
            }
            if($request->input('tags')){
                $tags = $request->input('tags');
                $this->classificationService->updateItems($project, explode(",", $tags), 'tags', Tags::class, 'name');
            }
            if($request->input('technologies')){
                $this->technologyService->updateTechnology($project, explode(",", $request->input('technologies')));
            }

            $replaceMiniature = filter_var($request->input('replace_miniature'), FILTER_VALIDATE_BOOLEAN);

            if($request->file('miniature')){
                $image = $request->file('miniature'); 
                if(!$request->input('id_miniature')){
                    $this->imageAssociationService->updateImage($project, $image, $replaceMiniature, 'miniature', 'project/miniature', $token);
                } else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('id_miniature')){
                $idImage = $request->input('id_miniature');
                if(!$request->file('miniature')){
                    $this->imageAssociationService->updateImageForId($project, $idImage, $replaceMiniature, 'miniature', $token);
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            $replaceImages = filter_var($request->input('replace_images'), FILTER_VALIDATE_BOOLEAN);

            if($request->file('images')){
                $images = $request->file('images'); 
                if(!$request->input('ids_images')){
                    foreach ($images as $image){
                        $this->imageAssociationService->updateImage($project, $image, $replaceImages, 'image', 'project/image', $token);
                    }
                } else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('ids_images')){
                $idImage = $request->input('ids_images');
                if(!$request->file('images')){
                    $ids = explode(",", $request->input('ids_images'));
                    foreach ($ids as $id) {
                        $this->imageAssociationService->updateImageForId($project, $id, $replaceImages, 'image', $token);
                    }
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
        
            $project->save();
            DB::commit(); // Confirmar la transacción
        
            return response()->json([
                'message' => 'Project updated successfully',
            ], 200);
        }catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Project not fount"
            ], 404);
        }
    }
}