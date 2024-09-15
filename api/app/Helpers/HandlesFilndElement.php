<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Helpers\HandleParameter;
use App\Helpers\PermissionsHelper;

class HandlesFilndElement
{

    protected $handleParameter;
    protected $permissionsHelper;

    public function __construct(HandleParameter $handleParameter, PermissionsHelper $permissionsHelper) {
        $this->handleParameter = $handleParameter;
        $this->permissionsHelper = $permissionsHelper;
    }

    public function findOne(string $modelClass, int $id = null, string $link = null, array $relations = []): ?Model
    {
        if ($link) {
            $model = $modelClass::where('link', $link)->first();
        } else {
            $model = $modelClass::find($id);
        }
    
        if (!$model) {
            throw new NotFoundHttpException("Not found");
        }
    
        $checkPermission = $this->permissionsHelper->seeHiddeItem($model);
    
        if ($relations) {
            $model->load($relations);
        }
    
        return $model;
    }

    public function findAll(string $modelClass, array $relations, array $query = []): ?array
    {
        $model = $modelClass::query();

        if (!empty($relations) && is_array($relations)) {
            $model = $model->with($relations);
        }

        $this->permissionsHelper->seeHiddeItems($model);
        
        $model->orderBy('created_at', 'desc');

        
        $pagination = $this->handleParameter->queryParameterPagination($query);
        $limit = $pagination['limit'];
        $offset = $pagination['offset'];
        
        
        $data = $model->limit($limit)->offset($offset)->get();

        $total = $model->count();
        $totalPages = (int) ceil($total / $limit);
        $currentPage = (int) ceil(($offset + 1) / $limit);

        return [
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'total' => $total,
            'perPage' => $limit,
            'data' => $data,
        ];
    }

}