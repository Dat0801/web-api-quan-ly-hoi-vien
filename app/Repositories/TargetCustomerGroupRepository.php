<?php

namespace App\Repositories;

use App\Interfaces\TargetCustomerGroupRepositoryInterface;
use App\Models\TargetCustomerGroup;

class TargetCustomerGroupRepository extends BaseRepository implements TargetCustomerGroupRepositoryInterface
{
    public function __construct(TargetCustomerGroup $model)
    {
        parent::__construct($model);
    }

    public function getTargetCustomerGroup(?string $search, int $perPage = 10)
    {
        $query = TargetCustomerGroup::query();
        
        if (!empty($search)) {
            $query->where('group_name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
