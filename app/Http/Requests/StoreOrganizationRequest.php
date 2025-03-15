<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreOrganizationRequest",
 *     title="Store Organization Request",
 *     description="Yêu cầu tạo mới tổ chức",
 *     required={"organization_code", "organization_name"},
 *     @OA\Property(property="organization_code", type="string", maxLength=50, example="IT"),
 *     @OA\Property(property="organization_name", type="string", maxLength=255, example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class StoreOrganizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'organization_code' => 'required|string|max:50|unique:organizations,organization_code',
            'organization_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
