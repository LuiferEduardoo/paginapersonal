<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HandlesFilndElement
{

    protected $handleParameter;

    public function findOne(string $modelClass, int $id, array $relations): ?Model
    {
        $model = $modelClass::find($id);

        if (!$model) {
            throw new NotFoundHttpException("Not found");
        }

        if($relations){
            $model->load($relations);
        }

        return $model;
    }
}