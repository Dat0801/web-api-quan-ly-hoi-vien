<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasPaginatedResource
{
    public static function collection($resource)
    {
        if ($resource instanceof LengthAwarePaginator) {
            return [
                'items' => parent::collection($resource)->collection,
                'pagination' => [
                    'current_page'  => $resource->currentPage(),
                    'per_page'      => $resource->perPage(),
                    'total'         => $resource->total(),
                    'last_page'     => $resource->lastPage(),
                    'next_page_url' => $resource->nextPageUrl(),
                    'prev_page_url' => $resource->previousPageUrl(),
                ],
            ];
        }

        return parent::collection($resource);
    }
}
