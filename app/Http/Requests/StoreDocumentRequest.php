<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'document' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg|max:5120', // 5MB
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'Vui lòng chọn tệp.',
            'document.file' => 'Tệp tải lên không hợp lệ.',
            'document.mimes' => 'Chỉ chấp nhận định dạng: PDF, DOC, DOCX, XLSX, XLS, PNG, JPG.',
            'document.max' => 'Tệp không được vượt quá 5MB.',
        ];
    }
}
