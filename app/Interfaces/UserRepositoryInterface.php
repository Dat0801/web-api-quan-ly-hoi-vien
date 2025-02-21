<?php

namespace App\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getUsers(?string $status, ?int $roleId, ?string $search);
}
