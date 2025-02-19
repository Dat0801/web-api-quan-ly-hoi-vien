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
 *     @OA\Property(property="roleCode", type="string", example="ADMIN001", description="Mã của vai trò."),
 *     @OA\Property(property="roleName", type="string", example="Quản trị viên", description="Tên của vai trò."),
 *     @OA\Property(
 *         property="permissions",
 *         type="array",
 *         description="Danh sách quyền của vai trò",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="groupName", type="string", example="Quản lý người dùng", description="Tên nhóm quyền."),
 *             @OA\Property(
 *                 property="permissions",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Permission")
 *             )
 *         )
 *     )
 * )
 */
class RoleResource extends JsonResource
{
    use HasPaginatedResource;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'roleCode' => $this->role_code,
            'roleName' => $this->role_name,
            $this->mergeWhen(!request()->is('api/users*'), [
                'permissions' => $this->groupPermissions(),
            ]),
        ];
    }

    private function groupPermissions()
    {
        return $this->permissions->groupBy('group_name')->map(function ($permissions, $groupName) {
            return [
                'groupName' => $groupName,
                'permissions' => PermissionResource::collection($permissions),
            ];
        })->values();
    }
}
