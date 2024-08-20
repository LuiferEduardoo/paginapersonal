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
                'link' => $this->link($request->input('name'), Projects::class),
                ]);
            $project->save(); // Se guarda la información del repositorio en la base de datos
            $this->saveImagesAndClassification($project, 'project/image', 'image', true, $request->hasFile('miniature'), $request->file('miniature'), $request->input('id_miniature'));
            return response()->json([
                'message' => 'Project successfully created'
            ], 200);
        });
    }

    public function deleteProject(Request $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $eliminateMiniature = filter_var($request->input('eliminate_miniature'), FILTER_VALIDATE_BOOLEAN);
            $project = Projects::find($id);
            if($project){
                $this->deleteImagesAndClassification($project, 'image', 'tecnologies', $eliminateMiniature);
                $project->delete();
                return response()->json(['message' => 'Project successfully deleted'], 200);
            }
            return response()->json([
                'message' => "Project not fount"
            ], 404);
        });
    }

    public function putProject(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $project = Projects::findOrFail($id);
            $urlRepository = $request->input('url_repository');

            // Se actualiza los campos de projects
            $project->name = $request->input('name');
            $project->link = $this->link($request->input('name'), $project);
            $project->brief_description = $request->input('brief_description');
            $project->url_repository = $urlRepository;
            $project->save();
            $this->updateImagesAndClassification($project, 'image', 'project/image', true, $request->hasFile('miniature'), $request->file('miniature'), $request->input('id_miniature'), $this->replaceMiniature); // Actualizamos las imagenes y clasificaciones
            return response()->json([
                'message' => 'Project successfully updated'
            ], 200);
        });
    }

    public function patchProject(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $project = Projects::find($id);
            if (!$project) {
                return response()->json(['message' => 'Project not found'], 404);
            }

            if ($request->input('name')) {
                $name = $request->input('name');
                $project->name = $name;
                $project->link = $this->link($name, $project);
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
            $this->updateImagesAndClassification($project, 'image', 'project/image', true, $request->hasFile('miniature'), $request->file('miniature'), $request->input('id_miniature'), $this->replaceMiniature); // Actualizamos las imagenes y clasificaciones
            $project->save();
            return response()->json([
                'message' => 'Project updated successfully',
            ], 200);
        });
    }
}