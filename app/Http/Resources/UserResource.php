<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Traits\HasPaginatedResource;

class UserResource extends JsonResource
{
    use HasPaginatedResource; 
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phoneNumber' => $this->phone_number,
            'role' => new RoleResource($this->role),
            'status' => $this->status,
            'avatar' => $this->avatar ? asset(path: 'storage/' . $this->avatar) : null,
            'lastLogin' => $this->last_login ? Carbon::parse($this->last_login)->format('Y-m-d H:i:s') : null,
        ];
    }
}
