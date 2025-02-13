<?php

namespace App\Interfaces;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllRoles(?string $filters);

}
