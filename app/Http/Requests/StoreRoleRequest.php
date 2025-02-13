<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_name' => 'required|string|max:255|unique:roles,role_name',
            'role_id' => 'required|string|max:50|unique:roles,role_id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'required|string|regex:/^\d+\.\d+$/',
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => 'Tên vai trò không được để trống.',
            'role_name.unique' => 'Tên vai trò đã tồn tại.',
            'role_id.required' => 'Mã vai trò không được để trống.',
            'role_id.unique' => 'Mã vai trò đã tồn tại.',
            'permissions.array' => 'Danh sách quyền phải là một mảng.',
            'permissions.*.required' => 'Mỗi quyền trong danh sách phải có giá trị.',
            'permissions.*.string' => 'Mỗi quyền phải là một chuỗi ký tự.',
            'permissions.*.regex' => 'Mỗi quyền phải có định dạng số, ví dụ: "1.1", "2.1".',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dữ liệu không hợp lệ!',
            'errors' => $validator->errors()
        ], 422));
    }
}
