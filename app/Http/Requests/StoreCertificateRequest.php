<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreCertificateRequest",
 *     title="Store Certificate Request",
 *     description="Yêu cầu tạo mới ngành nghề",
 *     required={"certificate_code", "certificate_name"},
 *     @OA\Property(property="certificate_code", type="string", maxLength=50, example="IT"),
 *     @OA\Property(property="certificate_name", type="string", maxLength=255, example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class StoreCertificateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'certificate_code' => 'required|string|max:50|unique:certificates,certificate_code',
            'certificate_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
