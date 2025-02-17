<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     @OA\Property(property="phone_number", type="string", nullable=true, example="0123456789"),
 *     @OA\Property(property="role_id", type="integer", example=2),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="avatar.jpg"),
 *     @OA\Property(property="password", type="string", format="password", nullable=true, example="newpassword123")
 * )
 */
class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => 'nullable|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}

