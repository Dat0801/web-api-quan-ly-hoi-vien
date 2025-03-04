<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreBusinessRequest",
 *     title="Store Business Request",
 *     description="Yêu cầu tạo mới ngành nghề",
 *     required={"business_code", "business_name"},
 *     @OA\Property(property="business_code", type="string", maxLength=50, example="IT"),
 *     @OA\Property(property="business_name", type="string", maxLength=255, example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class StoreBusinessRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'business_code' => 'required|string|max:50|unique:businesses,business_code',
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
