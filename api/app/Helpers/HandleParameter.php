<?php

namespace App\Helpers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HandleParameter
{
    public function queryParameterPagination(array $query = []): array
    {
        $limit = $query['limit'] ?? 10; // valor por defecto es 10
        $offset = $query['offset'] ?? 0;

        return [
            'limit' => (int) $limit,
            'offset' => (int) $offset * (int) $limit,
        ];
    }
}