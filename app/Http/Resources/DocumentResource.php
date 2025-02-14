<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasPaginatedResource;

class DocumentResource extends JsonResource
{
    use HasPaginatedResource; 
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'fileName'     => $this->file_name,
            'fileExtension'=> $this->file_extension,
        ];
    }
}
