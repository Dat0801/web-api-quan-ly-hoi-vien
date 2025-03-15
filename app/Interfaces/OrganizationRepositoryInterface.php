<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface OrganizationRepositoryInterface extends BaseRepositoryInterface
{
    public function getOrganization(?string $search, int $perPage);
}
