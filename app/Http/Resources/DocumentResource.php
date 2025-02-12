<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
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
            'file_name'     => $this->file_name,
            'file_extension'=> $this->file_extension,
            'file_path'     => asset('storage/' . $this->file_path),
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
