<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface BusinessRepositoryInterface extends BaseRepositoryInterface
{
    public function getBusiness(?string $search, int $perPage);
}
