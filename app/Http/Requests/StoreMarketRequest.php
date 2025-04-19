<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *     schema="StoreMarketRequest",
 *     title="Store Martket Request",
 *     required={"market_code", "market_name"},
 *     @OA\Property(property="market_code", type="string", maxLength=50, example="MK001"),
 *     @OA\Property(property="market_name", type="string", maxLength=255, example="Chợ Bến Thành"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Chợ nổi tiếng tại TP.HCM")
 * )
 */
class StoreMarketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'market_code' => 'required|string|max:50|unique:markets,market_code',
            'market_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
