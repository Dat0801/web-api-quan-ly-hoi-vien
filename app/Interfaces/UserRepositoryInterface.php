<?php

namespace App\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllUsers(?string $status, ?int $roleId, ?string $search);
}
