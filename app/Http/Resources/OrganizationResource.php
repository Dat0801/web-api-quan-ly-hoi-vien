<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Organization",
 *     title="Organization",
 *     description="Thông tin tổ chức",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="organization_code", type="string", example="IT"),
 *     @OA\Property(property="organization_name", type="string", example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class OrganizationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'organization_code' => $this->organization_code,
            'organization_name' => $this->organization_name,
            'description' => $this->description,
        ];
    }
}
