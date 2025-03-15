<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface FieldRepositoryInterface extends BaseRepositoryInterface
{
    public function getFields(?string $search, int $perPage);
}
