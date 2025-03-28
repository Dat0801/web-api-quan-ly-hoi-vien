<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTargetCustomerGroupRequest extends FormRequest 
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'group_code' => 'required|string|max:50|unique:target_customer_groups,group_code,' . $this->route('id'), 
            'group_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
