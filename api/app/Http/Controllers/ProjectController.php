<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateDate;
use App\Models\Projects;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    protected $replaceMiniature;

    public function constructor(Request $request){
        $this->replaceMiniature = filter_var($request->input('replace_miniature'), FILTER_VALIDATE_BOOLEAN);
    }
    public function getProject(Request $request){
        $query = Projects::with('repositories.categories', 'miniature', 'image', 'categories', 'subcategories', 'technology' ,'tags');
        if ($request->input('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }
        $query->where('visible', true);
        $query->orderBy('created_at', 'desc');
        $project = $query->get();
        return response()->json($project);
    }

    public function createProject(ValidateDate $request){
        return $this->executeInTransaction(function () use ($request) {
            $project = new Projects([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'brief_description' => $request->input('brief_description'),
                'link' => $this->link->generate($request->input('name'), Projects::class),
                ]);
            $project->save(); // Se guarda la información del repositorio en la base de datos
            $this->imageAssociationService->saveImages($project, $this->haveImages, $this->images, $this->ids_images, 'project/image', 'image', $this->token);
            $this->imageAssociationService->saveImages($project, true, $request->file('miniature'), $request->input('id_miniature'), 'project/miniature', 'miniature', $this->token);
            $this->saveClassification($project, true);
            return response()->json([
                'message' => 'Project successfully created'
            ], 200);
        });
    }

    public function deleteProject(Request $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $eliminateMiniature = filter_var($request->input('eliminate_miniature'), FILTER_VALIDATE_BOOLEAN);
            $project = $this->HandlesFilndElement->findOne(Projects::class, $id);
            if($project){
                $this->imageAssociationService->deleteImages($project, 'image', $this->eliminateImages, $this->token);
                $this->imageAssociationService->deleteImages($project, 'miniature', $eliminateMiniature, $this->token);
                $this->deleteClassification($project, 'tecnologies');
                $project->delete();
                return response()->json(['message' => 'Project successfully deleted'], 200);
            }
        });
    }

    public function updateProject(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $project = Projects::find($id);
            if (!$project) {
                return response()->json(['message' => 'Project not found'], 404);
            }

            if ($request->input('name')) {
                $name = $request->input('name');
                $project->name = $name;
                $project->link = $this->link->generate($name, $project);
            }
            if ($request->input('brief_description')) {
                $briefDescription = $request->input('brief_description');
                $project->brief_description = $briefDescription;
            }
            if ($request->input('description')) {
                $project->description = $request->input('description');
            }
            if ($request->input('project_link')) {
                $project->project_link = $request->input('project_link');
            }
            if ($request->input('visible') !== null) {
                $project->visible = $this->visible;
            }
            if($request->input('important') !== null) {
                $project->important = $this->important;
            }
            if($this->haveImages || $this->ids_images){
                $this->imageAssociationService->updateImages($project,  $this->haveImages, $this->images, $this->replaceImages, 'image',  $this->ids_images, 'project/image', $this->token);
            }
            $this->updateClassification($project, true);
            $project->save();
            return response()->json([
                'message' => 'Project updated successfully',
            ], 200);
        });
    }
}