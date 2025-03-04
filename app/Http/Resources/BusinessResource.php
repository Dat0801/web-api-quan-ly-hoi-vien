<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Business",
 *     title="Business",
 *     description="Thông tin ngành nghề",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="business_code", type="string", example="IT"),
 *     @OA\Property(property="business_name", type="string", example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class BusinessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'business_code' => $this->business_code,
            'business_name' => $this->business_name,
            'description' => $this->description,
        ];
    }
}
