<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldRequest extends FormRequest
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
        $fieldId = $this->route('id'); 

        return [
            'name' => 'required|string|max:255',
            'code' => "required|string|max:50|unique:fields,code,{$fieldId}",
            'description' => 'nullable|string|max:1000',
            'industry_id' => 'required|exists:industries,id',
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
            'sub_groups.*.id.exists' => 'Nhóm con không hợp lệ.',
            'sub_groups.*.name.required' => 'Tên nhóm con là bắt buộc.',
        ];
    }
}
