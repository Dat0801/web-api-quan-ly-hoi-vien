<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     required={"name", "email", "password", "passwordConfirmation", "phoneNumber" , "roleId", "status"},
 *     @OA\Property(property="name", type="string", example="Thành Đạt"),
 *     @OA\Property(property="email", type="string", format="email", example="thanhdat@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 *     @OA\Property(property="passwordConfirmation", type="string", format="password", example="password123"),  
 *     @OA\Property(property="phoneNumber", type="string", nullable=true, example="0399839848"),
 *     @OA\Property(property="roleId", type="integer", example=1),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="avatar", type="file")
 * )
 */

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phoneNumber' => 'nullable|string|max:15',
            'roleId' => 'required|exists:roles,id',
            'status' => 'required',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('passwordConfirmation')) {
            $this->merge([
                'password_confirmation' => $this->input('passwordConfirmation'),
            ]);
        }
    }

}


