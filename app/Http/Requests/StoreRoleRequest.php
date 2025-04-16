<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * @OA\Schema(
 *     schema="StoreRoleRequest",
 *     title="Store Role Request",
 *     required={"role_name", "role_id", "permission_ids"},
 *     description="Yêu cầu tạo mới vai trò",
 *     @OA\Property(property="role_name", type="string", example="Quản trị viên", description="Tên vai trò."),
 *     @OA\Property(property="role_id", type="string", example="ADMIN001", description="Mã vai trò duy nhất."),
 *     @OA\Property(
 *         property="permission_ids",
 *         type="array",
 *         description="Danh sách quyền được cấp cho vai trò.",
 *         @OA\Items(type="string", example="1")
 *     )
 * )
 */

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
            'permission_ids' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => 'Tên vai trò không được để trống.',
            'role_name.unique' => 'Tên vai trò đã tồn tại.',
            'role_id.required' => 'Mã vai trò không được để trống.',
            'role_id.unique' => 'Mã vai trò đã tồn tại.',
            'permission_ids.array' => 'Danh sách quyền phải là một mảng.',
            'permission_ids.required' => 'Danh sách quyền không được để trống.',
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
