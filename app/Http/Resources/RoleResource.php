<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
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
        $grouped = $this->permissions->groupBy('group_name')->map(function ($permissions, $groupName) {
            return [
                'groupName' => $groupName,
                'permissions' => PermissionResource::collection($permissions),
            ];
        });

        return $grouped->values();
    }
}
