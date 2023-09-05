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
            $this->saveImagesAndClassification($skill, 'skill', 'image');
            return response()->json([
                'message' => 'Skills successfully created'
            ], 200);
        });
    }

    public function deleteSkills(ValidateDate $request){
        return $this->executeInTransaction(function () use ($request) {
            $id = $request->input('id');
            if(Skills::findOrFail($id)){
                $skill = Skills::findOrFail($id);
                $this->deleteImagesAndClassification($skill, 'image');
                $skill->delete();
                return response()->json(['message' => 'Skills successfully deleted'],200);
            }
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        });
    }

    public function putSkills(ValidateDate $request, $id){
        return $this->executeInTransaction(function () use ($request, $id) {
            $skill = Skills::findOrFail($id);
            
            $skill->name = $request->input('name'); // Actualizar los campos de la habilidad
            $skill->date = $request->input('date'); // Guardar los cambios en la base de datos
            $skill->save(); // Guardamos los datos
            $this->updateImagesAndClassification($skill, 'image', 'skill'); // Actualizamos las imagenes y clasificaciones
            return response()->json([
                'message' => 'Skill successfully updated'
            ], 200);
        });
    }

    public function patchSkills(ValidateDate $request, $id){
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
            $this->updateImagesAndClassification($skill, 'image', 'skill'); // Actualizamos las imagenes y clasificaciones
            $skill->save();
            return response()->json([
                'message' => 'Skill updated successfully',
            ], 200);
        });
    }
}