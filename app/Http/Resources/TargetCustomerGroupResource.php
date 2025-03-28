<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TargetCustomerGroup",
 *     title="TargetCustomerGroup",
 *     description="Thông tin nhóm khách hàng",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="group_code", type="string", example="IT"),
 *     @OA\Property(property="group_name", type="string", example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class TargetCustomerGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_code' => $this->group_code,
            'group_name' => $this->group_name,
            'description' => $this->description,
        ];
    }
}
