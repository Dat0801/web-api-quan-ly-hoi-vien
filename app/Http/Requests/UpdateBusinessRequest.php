<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'business_code' => 'required|string|max:50|unique:businesses,business_code,' . $this->route('id'), 
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
