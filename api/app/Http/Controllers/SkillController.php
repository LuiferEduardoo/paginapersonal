<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skills;
use App\Http\Requests\ValidateDate;
use Illuminate\Support\Facades\Storage;

class SkillController extends Controller
{

    public function getSkills(Request $request){
        $query = Skills::with('image', 'categories', 'subcategories', 'tags');
        if ($request->input('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }
        $query->where('visible', true);
        $skills = $query->get();
        return response()->json($skills);
    }

    public function createSkills(ValidateDate $request)
    {
        return $this->executeInTransaction(function () use ($request) {
            // Crea la habilidad
            $skill = Skills::create([
                'name' => $request->input('name'),
                'date' => $request->input('date'),
            ]);
            $this->imageAssociationService->saveImages($skill, $this->haveImages, $this->images, $this->ids_images, 'skill', 'image', $this->token);
            $this->saveClassification($skill);
            return response()->json([
                'message' => 'Skills successfully created'
            ], 200);
        });
    }

    public function deleteSkills(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $skill = Skills::find($id);
            if($skill){
                $this->imageAssociationService->deleteImages($skill, 'image', $this->eliminateImages, $this->token);
                $this->deleteClassification($skill);
                $skill->delete();
                return response()->json(['message' => 'Skills successfully deleted'],200);
            }
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        });
    }

    public function updateSkills(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $skill = Skills::find($id);
            if ($request->input('name')) {
                $skill->name = $request->input('name');
            }
            if ($request->input('date')) {
                $skill->date = $request->input('date');
            }
            if ($request->input('visible')) {
                $skill->visible = $this->visible;
            }
            if($this->haveImages || $this->ids_images){
                $this->imageAssociationService->updateImages($skill,  $this->haveImages, $this->images, $this->replaceImages, 'image',  $this->ids_images, 'skill', $this->token);
            }
            $this->updateClassification($skill); // Actualizamos las imagenes y clasificaciones
            $skill->save();
            return response()->json([
                'message' => 'Skill updated successfully',
            ], 200);
        });
    }
}