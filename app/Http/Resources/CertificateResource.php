<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Certificate",
 *     title="Certificate",
 *     description="Thông tin ngành nghề",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="certificate_code", type="string", example="IT"),
 *     @OA\Property(property="certificate_name", type="string", example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class CertificateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'certificate_code' => $this->certificate_code,
            'certificate_name' => $this->certificate_name,
            'description' => $this->description,
        ];
    }
}
