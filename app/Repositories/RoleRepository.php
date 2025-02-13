<?php

namespace App\Repositories;

use App\Models\Role;
use App\Interfaces\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getAllRoles($filters)
    {
        return $this->model->with('permissions')
            ->when(isset($filters['search']), function ($query) use ($filters) {
                return $query->where('role_name', 'like', '%' . $filters['search'] . '%');
            })
            ->paginate(10);
    }
}
