<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasPaginatedResource;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Role",
 *     description="Thông tin vai trò",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của vai trò."),
 *     @OA\Property(property="role_id", type="string", example="ADMIN001", description="Mã của vai trò."),
 *     @OA\Property(property="role_name", type="string", example="Quản trị viên", description="Tên của vai trò."),
 *     @OA\Property(property="permission_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}, description="Danh sách ID quyền của vai trò."),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-17T12:30:45Z", description="Thời gian tạo vai trò."),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-17T12:30:45Z", description="Thời gian cập nhật vai trò.")
 * )
 */
class RoleResource extends JsonResource
{
    use HasPaginatedResource;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'role_name' => $this->role_name,
            'permission_ids' => $this->permissions->pluck('id')->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
