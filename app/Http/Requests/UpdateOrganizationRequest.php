<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'organization_code' => 'required|string|max:50|unique:organizations,organization_code,' . $this->route('id'), 
            'organization_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
