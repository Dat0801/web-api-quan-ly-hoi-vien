<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Vai trò người dùng",
 *     description="Model Role",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="role_id", type="integer", example=2),
 *     @OA\Property(property="role_name", type="string", example="Admin")
 * )
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'role_name',
    ];

    /**
     * Danh sách người dùng thuộc vai trò này
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Danh sách quyền của vai trò
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}

