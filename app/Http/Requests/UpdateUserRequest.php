<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     title="Update User Request",
 *     required={"_method", "password", "passwordConfirmation", "phoneNumber", "roleId", "status"},
 *     description="Yêu cầu cập nhật thông tin người dùng",
 *     @OA\Property(property="_method", type="string", example="PUT", description="Giả lập phương thức PUT để cập nhật tài nguyên."),
 *     @OA\Property(property="phoneNumber", type="string", example="0123456789", description="Số điện thoại của người dùng."),
 *     @OA\Property(property="avatar", type="string", format="binary", description="Ảnh đại diện của người dùng (file ảnh)."),
 *     @OA\Property(property="roleId", type="integer", example=2, description="ID của vai trò mới của người dùng."),
 *     @OA\Property(property="status", type="boolean", example=true, description="Trạng thái tài khoản (true: hoạt động, false: bị khóa)."),
 *     @OA\Property(property="password", type="string", format="password", example="newpassword123", description="Mật khẩu mới, tối thiểu 8 ký tự, cần nhập lại để xác nhận."),
 *     @OA\Property(property="passwordConfirmation", type="string", format="password", example="newpassword123", description="Xác nhận mật khẩu mới của người dùng.")
 * )
 */

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'phone_number' => $this->input('phoneNumber'),
            'role_id' => $this->input('roleId'),
            'password_confirmation' => $this->input('passwordConfirmation'),
        ]);
    }
}
