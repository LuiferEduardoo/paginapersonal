<?php

namespace App\Helpers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HandlesFilndElement
{
    public function findOne($modelClass, $id)
    {
        $model = $modelClass::find($id);

        if (!$model) {
            throw new NotFoundHttpException("Not found");
        }

        return $model;
    }
}