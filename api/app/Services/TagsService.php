<?php
namespace App\Services;

use App\Models\Tags;

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
}