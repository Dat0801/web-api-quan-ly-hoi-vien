<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreTargetCustomerGroupRequest",
 *     title="Store Target Customer Group Request",
 *     description="Yêu cầu tạo mới nhóm khách hàng",
 *     required={"group_code", "group_name"},
 *     @OA\Property(property="group_code", type="string", maxLength=50, example="IT"),
 *     @OA\Property(property="group_name", type="string", maxLength=255, example="Công nghệ thông tin"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Ngành liên quan đến phần mềm, phần cứng và công nghệ"),
 * )
 */
class StoreTargetCustomerGroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'group_code' => 'required|string|max:50|unique:target_customer_groups,group_code',
            'group_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
