<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $this->role,
            'role_id' => 'required|string|max:50|unique:roles,role_id,' . $this->role,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => 'Tên vai trò không được để trống.',
            'role_name.unique' => 'Tên vai trò đã tồn tại.',
            'role_id.required' => 'Mã vai trò không được để trống.',
            'role_id.unique' => 'Mã vai trò đã tồn tại.',
            'permissions.array' => 'Danh sách quyền không hợp lệ.',
        ];
    }
}
