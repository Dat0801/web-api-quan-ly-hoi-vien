<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login_code' => 'sometimes|string|max:255|unique:board_customers,login_code,' . $this->route('id'),
            'card_code' => 'sometimes|string|max:255|unique:board_customers,card_code,' . $this->route('id'),
            'full_name' => 'sometimes|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'sometimes|in:male,female,other',
            'phone' => 'sometimes|string|max:15',
            'email' => 'sometimes|email|max:255',
            'unit_name' => 'sometimes|string|max:255',
            'unit_position' => 'sometimes|string|max:255',
            'association_position' => 'sometimes|string|max:255',
            'term' => 'sometimes|integer|min:1900|max:2100',
            'attendance_permission' => 'nullable|boolean',
            'avatar' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'login_code.unique' => 'Mã đăng nhập phải là duy nhất.',
            'card_code.unique' => 'Mã thẻ phải là duy nhất.',
            'email.email' => 'Địa chỉ email phải là hợp lệ.',
            'term.integer' => 'Nhiệm kỳ phải là một số nguyên.',
            'term.min' => 'Nhiệm kỳ phải lớn hơn hoặc bằng 1900.',
            'term.max' => 'Nhiệm kỳ phải nhỏ hơn hoặc bằng 2100.',
        ];
    }
}