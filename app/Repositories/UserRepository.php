<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    
    public function getAllUsers(?string $status, ?int $roleId, ?string $search)
    {
        return User::query()
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($roleId, fn($query) => $query->where('role_id', $roleId))
            ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
            ->paginate(10);
    }
}
