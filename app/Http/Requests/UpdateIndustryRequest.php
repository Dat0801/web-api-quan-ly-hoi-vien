<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndustryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'industry_code' => 'required|string|max:50|unique:industries,industry_code,' . $this->route('id'),
            'industry_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
