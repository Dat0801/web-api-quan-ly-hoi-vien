<?php

namespace App\Interfaces;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function getRoles(string $filters);

}
