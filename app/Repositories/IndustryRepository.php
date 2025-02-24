<?php

namespace App\Repositories;

use App\Models\Industry;
use App\Interfaces\IndustryRepositoryInterface;

class IndustryRepository extends BaseRepository implements IndustryRepositoryInterface 
{
    public function __construct(Industry $model)
    {
        parent::__construct($model);
    }

    public function getIndustries($search, $perPage)
    {
        return $this->model->where('industry_code', 'like', '%' . $search . '%')
            ->orWhere('industry_name', 'like', '%' . $search . '%')
            ->paginate($perPage);
    }
}
