<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoardCustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login_code' => $this->login_code,
            'card_code' => $this->card_code,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'email' => $this->email,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'unit_name' => $this->unit_name,
            'unit_position' => $this->unit_position,
            'association_position' => $this->association_position,
            'term' => $this->term,
            'attendance_permission' => $this->attendance_permission,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
