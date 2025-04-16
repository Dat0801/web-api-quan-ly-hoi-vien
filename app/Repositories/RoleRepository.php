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

    public function getRoles(?string $filter = null)     
    {
        return $this->model
        ->with('permissions') 
        ->when(!empty($filter), function ($query) use ($filter) {
            $query->where('role_name', 'like', '%' . $filter . '%');
        })
        ->paginate(10);
    }
}
