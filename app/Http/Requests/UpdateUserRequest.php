<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     title="Update User Request",
 *     required={"password", "phone_number", "role_id", "status"},
 *     description="Yêu cầu cập nhật thông tin người dùng",
 *     @OA\Property(property="phone_number", type="string", example="0123456789", description="Số điện thoại của người dùng."),
 *     @OA\Property(property="avatar", type="string", format="binary", description="Ảnh đại diện của người dùng (file ảnh)."),
 *     @OA\Property(property="role_id", type="integer", example=2, description="ID của vai trò mới của người dùng."),
 *     @OA\Property(property="status", type="boolean", example=true, description="Trạng thái tài khoản (true: hoạt động, false: bị khóa)."),
 *     @OA\Property(property="password", type="string", format="password", example="newpassword123", description="Mật khẩu mới, tối thiểu 8 ký tự, cần nhập lại để xác nhận."),
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
            'avatar' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
