<?php

namespace App\Repositories;

use App\Interfaces\BusinessRepositoryInterface;
use App\Models\Business;

class BusinessRepository extends BaseRepository implements BusinessRepositoryInterface
{
    public function __construct(Business $model)
    {
        parent::__construct($model);
    }

    public function getBusiness(?string $search, int $perPage = 10)
    {
        $query = Business::query();

        if (!empty($search)) {
            $query->where('business_name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
