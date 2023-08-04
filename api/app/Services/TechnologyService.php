<?php 

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TechnologyService
{
    public function addTechnology(Model $object, array $ids){
        try{
            // Asociar las tecnologias con el objeto
            $object->technology()->attach($ids);
        } catch (\Exception $e) {
            // Manejo de la excepción
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function deleteTechnology(Model $object){
        try{
            $object->technology()->detach();
        }  catch (\Exception $e) {
            // Manejo de la excepción
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateTechnology(Model $object, array $ids){
        try{
            $this->deleteTechnology($object);
            $this->addTechnology($object, $ids);
        } catch (\Exception $e) {
            // Manejo de la excepción
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }
}