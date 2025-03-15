<?php

namespace App\Repositories;

use App\Interfaces\OrganizationRepositoryInterface;
use App\Models\Organization;

class OrganizationRepository extends BaseRepository implements OrganizationRepositoryInterface
{
    public function __construct(Organization $model)
    {
        parent::__construct($model);
    }

    public function getOrganization(?string $search, int $perPage = 10)
    {
        $query = Organization::query();
        
        if (!empty($search)) {
            $query->where('organization_name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
