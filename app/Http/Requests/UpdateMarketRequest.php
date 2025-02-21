<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     schema="UpdateMarketRequest",
 *     required={"marketCode", "marketName"},
 *     @OA\Property(property="marketCode", type="string", maxLength=50, example="MK001"),
 *     @OA\Property(property="marketName", type="string", maxLength=255, example="Chợ Bến Thành"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Chợ nổi tiếng tại TP.HCM")
 * )
 */
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
