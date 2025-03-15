<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreFieldRequest",
 *     title="Store Field Request",
 *     required={"name", "code", "industry_id"},
 *     @OA\Property(property="name", type="string", example="Lập trình phần mềm", description="Tên của lĩnh vực"),
 *     @OA\Property(property="code", type="string", example="IT-SW", description="Mã lĩnh vực, phải là duy nhất"),
 *     @OA\Property(property="description", type="string", example="Lĩnh vực phát triển phần mềm", description="Mô tả lĩnh vực"),
 *     @OA\Property(property="industry_id", type="integer", example=1, description="ID của ngành nghề liên quan"),
 * )
 */
class StoreFieldRequest extends FormRequest
{
    /**
     * Xác định người dùng có quyền gửi request này không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Quy tắc kiểm tra dữ liệu đầu vào.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fields,code',
            'description' => 'nullable|string|max:1000',
            'industry_id' => 'required|exists:industries,id',
            'sub_groups' => 'nullable|array',
            'sub_groups.*.name' => 'required|string|max:255',
            'sub_groups.*.description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên lĩnh vực là bắt buộc.',
            'code.required' => 'Mã lĩnh vực là bắt buộc.',
            'code.unique' => 'Mã lĩnh vực đã tồn tại.',
            'industry_id.required' => 'Ngành nghề là bắt buộc.',
            'industry_id.exists' => 'Ngành nghề không tồn tại.',
            'sub_groups.*.name.required' => 'Tên nhóm con là bắt buộc.',
        ];
    }
}
