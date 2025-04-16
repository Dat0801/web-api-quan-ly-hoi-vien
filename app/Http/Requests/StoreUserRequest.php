<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     title="Store User Request",
 *     required={"name", "email", "password", "phone_number", "role_id", "status"},
 *     description="Yêu cầu tạo mới người dùng",
 *     @OA\Property(property="name", type="string", example="Thành Đạt", description="Tên đầy đủ của người dùng."),
 *     @OA\Property(property="email", type="string", format="email", example="thanhdat@example.com", description="Địa chỉ email của người dùng."),
 *     @OA\Property(property="phone_number", type="string", example="0399839848", description="Số điện thoại của người dùng."),
 *     @OA\Property(property="avatar", type="file", description="Ảnh đại diện của người dùng (file ảnh)."),
 *     @OA\Property(property="role_id", type="integer", example=1, description="ID vai trò của người dùng."),
 *     @OA\Property(property="status", type="boolean", example=true, description="Trạng thái tài khoản (true: hoạt động, false: bị khóa)."),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Mật khẩu của người dùng, tối thiểu 8 ký tự."),
 * )
 */

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required',
            'avatar' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


