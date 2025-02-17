<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasPaginatedResource;

/**
 * @OA\Schema(
 *     schema="RoleResource",
 *     title="Role Resource",
 *     description="Thông tin vai trò",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="roleCode", type="integer", example=2),
 *     @OA\Property(property="roleName", type="string", example="Quản trị viên"),
 *     @OA\Property(
 *         property="permissions",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PermissionResource")
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
            'roleCode' => $this->role_id,
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
