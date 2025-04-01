<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface BoardCustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function getBoardCustomers(?string $search, int $perPage, $status);
}
