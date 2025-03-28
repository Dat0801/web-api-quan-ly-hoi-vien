<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface TargetCustomerGroupRepositoryInterface extends BaseRepositoryInterface
{
    public function getTargetCustomerGroup(?string $search, int $perPage);
}
