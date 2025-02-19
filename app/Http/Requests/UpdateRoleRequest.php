<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * @OA\Schema(
 *     schema="UpdateRoleRequest",
 *     title="Update Role Request",
 *     description="Yêu cầu cập nhật vai trò",
 *     @OA\Property(property="roleName", type="string", example="Quản trị viên", description="Tên vai trò."),
 *     @OA\Property(
 *         property="permissions",
 *         type="array",
 *         description="Danh sách quyền được cấp cho vai trò.",
 *         @OA\Items(type="string", example="1.1")
 *     )
 * )
 */

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $data = [];

        if ($this->has('roleName')) {
            $data['role_name'] = $this->input('roleName');
        }

        if (!empty($data)) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        return [
            'role_name' => 'sometimes|string|max:255|unique:roles,role_name,' . $this->route('id'),
            'permissions' => 'sometimes|array',
        ];
    }

    public function messages(): array
    {
        return [
            'role_name.string' => 'Tên vai trò phải là chuỗi.',
            'role_name.max' => 'Tên vai trò không được vượt quá 255 ký tự.',
            'role_name.unique' => 'Tên vai trò đã tồn tại.',
            'permissions.array' => 'Danh sách quyền phải là một mảng.',
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

