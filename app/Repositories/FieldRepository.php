<?php

namespace App\Repositories;

use App\Models\Field;
use App\Interfaces\FieldRepositoryInterface;

class FieldRepository extends BaseRepository implements FieldRepositoryInterface
{
    public function __construct(Field $model)
    {
        parent::__construct($model);
    }

    public function getFields(?string $search, int $perPage = 10)
    {
        $query = Field::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
