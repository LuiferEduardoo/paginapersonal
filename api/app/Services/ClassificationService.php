<?php 

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class ClassificationService
{
    public function createItems(Model $object, $arrayItems, $relation, $itemModel, $itemNameField)
    {
        try {
            // Obtener los nombres de los items existentes
            $existingItems = $itemModel::whereIn($itemNameField, $arrayItems)->get();
            $existingItemNames = $existingItems->pluck($itemNameField)->toArray();

            // Filtrar los nuevos items que no existen
            $newItems = collect($arrayItems)->reject(function ($itemName) use ($existingItemNames) {
                return in_array($itemName, $existingItemNames);
            });

            // Crear los nuevos items y obtener sus IDs
            $newItemIds = $newItems->map(function ($itemName) use ($itemModel, $itemNameField) {
                $newItem = $itemModel::create([$itemNameField => $itemName]);
                return $newItem->id;
            });

            // Obtener los IDs de todos los items (existentes y nuevos)
            $itemIds = $existingItems->pluck('id')->concat($newItemIds);

            // Asociar los items con el objeto
            $object->$relation()->sync($itemIds);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteItems(Model $object, $relation)
    {
        try {
            $object->$relation()->detach();
        } catch (\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function updateItems(Model $object, $arrayItems, $relation, $itemModel, $itemNameField){
        try{
            $this->deleteItems($object, $relation);
            $this->createItems($object, $arrayItems, $relation, $itemModel, $itemNameField);
        } catch (\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }
}