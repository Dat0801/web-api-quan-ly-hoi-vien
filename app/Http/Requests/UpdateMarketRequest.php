<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'market_code' => 'required|string|max:50|unique:markets,market_code,' . $this->route('id'),
            'market_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'market_code' => $this->input('marketCode'),
            'market_name' => $this->input('marketName'),
        ]);
    }
}
