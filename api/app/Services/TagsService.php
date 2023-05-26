<?php
namespace App\Services;

use App\Models\Tags;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TagsService
{
    public function createAndAttachTags(array $tags): array
    {
        $existingTags = Tags::whereIn('name', $tags)->get();

        // Elimina los tags existentes del arreglo de nombres de tags
        $newTags = collect($tags)->diff($existingTags->pluck('name'))->map(function ($tagName) {
            return ['name' => $tagName];
        });

        // Crear los nuevos tags
        $newTagModels = Tags::insert($newTags->toArray());

        // Obtener los IDs de todos los tags
        $tagIds = $existingTags->union($newTagModels)->pluck('id');

        return $tagIds->toArray();
    }

    public function deleteTags(Model $object, $relation){
        try{
            $tagIds = $object->tags->pluck('id');
            $object->$relation()->detach($tagIds);
        } catch(\Exception $e) {
            // Manejo de la excepción
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function createTags(Model $object, $arrayTags, $relation){
        try{
            $createTags = $this->createAndAttachTags($arrayTags);
            $object->$relation()->attach($createTags);
        } catch(\Exception $e) {
            // Manejo de la excepción
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateTags(Model $object, $arrayTags, $relation){
        try{
            // Se obtiene los IDs de los tags
            $tagIds = $this->createAndAttachTags($arrayTags);
    
            // Obtener los IDs de los tags existentes asociados a la habilidad
            $existingTagIds = $object->$relation()->pluck('tags.id')->toArray();
    
            // Comparar los IDs de los tags existentes con los nuevos tags
            if ($existingTagIds != $tagIds) {
                // Eliminar las asociaciones existentes
                $object->$relation()->detach();
    
                // Asociar los tags con la nueva habilidad
                $object->$relation()->attach($tagIds);
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }
}