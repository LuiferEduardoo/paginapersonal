<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateDate;
use App\Models\Projects;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Subcategories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProjectController extends Controller
{
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
                'link' => $this->link($request->input('name'), Projects::class),
                ]);
            
            $project->save(); // Se guarda la información del repositorio en la base de datos

            $this->githubService->getInformationRepository($project, $repositoryUrl); // Se obtiene la información de repositorio en github

            //Se guardan las imagenes tanto por su id como por medio de un archivo tipo imagen
            $this->imageAssociationService->saveImages($project, $request->hasFile('miniature'), $request->file('miniature'), $request->input('id_miniature'), 'project/miniature', 'miniature', $token);
            $this->imageAssociationService->saveImages($project, $request->hasFile('images'), $request->file('images'), $request->input('ids_images'), 'project/image', 'image', $token);

            //Se hacen y se guardan las clasificaciones 
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
            $eliminateImages =  filter_var($request->input('eliminate_images'), FILTER_VALIDATE_BOOLEAN);
            $eliminateMiniature = filter_var($request->input('eliminate_miniature'), FILTER_VALIDATE_BOOLEAN);

            if(Projects::findOrFail($id)){
                $project = Projects::findOrFail($id);
                $items = array('tags', 'categories', 'subcategories');
                foreach ($items as $item){
                    $this->classificationService->deleteItems($project, $item);
                }
                $this->githubService->deleteAllRelations($project); // Se borran todas las relacciones de la información traida de github
                
                $this->technologyService->deleteTechnology($project); // Se borran las tecnologias asociadas a el proyecto
                $this->imageAssociationService->deleteImages($project, 'image', $eliminateImages, $token); // Se borra la imagen
                $this->imageAssociationService->deleteImages($project, 'miniature', $eliminateMiniature, $token); // Se borra la miniatura
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
            $this->imageAssociationService->updateImages($proyect, $request->hasFile('miniature'), $request->file('miniature'), $replaceMiniature, 'miniature', $request->input('id_miniature'),'project/miniature', $token);
    
            $replaceImages = filter_var($request->input('replace_images'), FILTER_VALIDATE_BOOLEAN);
            $this->imageAssociationService->updateImages($proyect, $request->hasFile('images'), $request->file('images'), $replaceImages, 'image', $request->input('ids_images'), 'project/image', $token);

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

            if($request->hasFile('miniature') || $request->input('id_miniature') ){
                $this->imageAssociationService->updateImages($project, $request->hasFile('miniature'), $request->file('miniature'), $replaceMiniature, 'miniature', $request->input('id_miniature'),'project/miniature', $token);
            }

            $replaceImages = filter_var($request->input('replace_images'), FILTER_VALIDATE_BOOLEAN);

            if($request->hasFile('images') || $request->input('ids_images')){
                $this->imageAssociationService->updateImages($project, $request->hasFile('images'), $request->file('images'), $replaceImages, 'image', $request->input('ids_images'), 'project/image', $token);
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