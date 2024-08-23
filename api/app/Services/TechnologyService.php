<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class TechnologyService
{
    public function addTechnology(Model $object, array $ids)
    {
        try {
            $object->technology()->attach($ids);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function deleteTechnology(Model $object)
    {
        try {
            $object->technology()->detach();
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateTechnology(Model $object, array $ids)
    {
        $response = $this->deleteTechnology($object);
        if ($response instanceof JsonResponse) {
            return $response;
        }

        $response = $this->addTechnology($object, $ids);
        if ($response instanceof JsonResponse) {
            return $response;
        }
    }
}