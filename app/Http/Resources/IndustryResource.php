<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Industry",
 *     title="Industry",
 *     description="Thông tin ngành nghề",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="industry_code", type="string", example="IT"),
 *     @OA\Property(property="industry_name", type="string", example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class IndustryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'industry_code' => $this->industry_code,
            'industry_name' => $this->industry_name,
            'description' => $this->description,
        ];
    }
}
