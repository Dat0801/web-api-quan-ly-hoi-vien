<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     title="Store User Request",
 *     required={"name", "email", "password", "passwordConfirmation", "phoneNumber", "roleId", "status"},
 *     description="Yêu cầu tạo mới người dùng",
 *     @OA\Property(property="name", type="string", example="Thành Đạt", description="Tên đầy đủ của người dùng."),
 *     @OA\Property(property="email", type="string", format="email", example="thanhdat@example.com", description="Địa chỉ email của người dùng."),
 *     @OA\Property(property="phoneNumber", type="string", example="0399839848", description="Số điện thoại của người dùng."),
 *     @OA\Property(property="avatar", type="file", description="Ảnh đại diện của người dùng (file ảnh)."),
 *     @OA\Property(property="roleId", type="integer", example=1, description="ID vai trò của người dùng."),
 *     @OA\Property(property="status", type="boolean", example=true, description="Trạng thái tài khoản (true: hoạt động, false: bị khóa)."),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Mật khẩu của người dùng, tối thiểu 8 ký tự."),
 *     @OA\Property(property="passwordConfirmation", type="string", format="password", example="password123", description="Xác nhận mật khẩu của người dùng.")
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
            'phone_number' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }

    public function prepareForValidation()
    {
        $data = [];
    
        if ($this->has('passwordConfirmation')) {
            $data['password_confirmation'] = $this->input('passwordConfirmation');
        }
    
        if ($this->has('roleId')) {
            $data['role_id'] = $this->input('roleId');
        }
    
        if ($this->has('phoneNumber')) {
            $data['phone_number'] = $this->input('phoneNumber');
        }
    
        if (!empty($data)) {
            $this->merge($data);
        }
    }
}


