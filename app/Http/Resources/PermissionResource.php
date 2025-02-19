<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Permission",
 *     title="Permission",
 *     description="Thông tin quyền",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của quyền."),
 *     @OA\Property(property="name", type="string", example="Xem người dùng", description="Tên của quyền.")
 * )
 */
class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->permission_name,
        ];
    }
}