<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllUsers(?string $status, ?int $roleId, ?string $search);
}
