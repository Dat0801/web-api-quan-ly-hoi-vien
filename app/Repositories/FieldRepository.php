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

    public function getFields(?string $search, int $perPage = 10, ?string $include = null)
    {
        $query = Field::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($include === 'industry') {
            $query->with('industry');
        }

        return $query->paginate($perPage);
    }
}
