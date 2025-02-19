<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasPaginatedResource;

/**
 * @OA\Schema(
 *     schema="Document",
 *     title="Document",
 *     description="Chi tiết tài liệu",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="fileName", type="string", example="document.pdf"),
 *     @OA\Property(property="fileExtension", type="string", example="pdf"),
 *     @OA\Property(property="filePath", type="string", example="documents/document.pdf")
 * )
 */
class DocumentResource extends JsonResource
{
    use HasPaginatedResource;

    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'fileName'      => $this->file_name,
            'fileExtension' => $this->file_extension,
            'filePath' => $this->file_path
        ];
    }
}
