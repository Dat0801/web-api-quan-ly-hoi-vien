<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreIndustryRequest",
 *     title="Store Industry Request",
 *     description="Yêu cầu tạo mới ngành nghề",
 *     required={"industry_code", "industry_name"},
 *     @OA\Property(property="industry_code", type="string", maxLength=50, example="IT"),
 *     @OA\Property(property="industry_name", type="string", maxLength=255, example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class StoreIndustryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'industry_code' => 'required|string|max:50|unique:industries,industry_code',
            'industry_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
