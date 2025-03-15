<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Field",
 *     title="Field",
 *     description="Thông tin lĩnh vực",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="code", type="string", example="IT-DEV"),
 *     @OA\Property(property="name", type="string", example="Phát triển phần mềm"),
 *     @OA\Property(property="description", type="string", example="Lĩnh vực chuyên về phát triển phần mềm, ứng dụng và hệ thống"),
 *     @OA\Property(property="industry_id", type="integer", example=2),
 * )
 */
class FieldResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'industry_id' => $this->industry_id,
        ];
    }
}
