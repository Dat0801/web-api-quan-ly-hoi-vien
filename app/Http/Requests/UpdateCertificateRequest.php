<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'certificate_code' => 'required|string|max:50|unique:certificates,certificate_code,' . $this->route('id'), 
            'certificate_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
