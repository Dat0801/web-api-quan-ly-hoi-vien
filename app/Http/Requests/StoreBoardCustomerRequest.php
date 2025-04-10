<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BoardCustomerRequest",
 *     required={"login_code", "card_code", "full_name", "gender", "phone", "email", "unit_name", "unit_position", "association_position", "term"},
 *     @OA\Property(property="login_code", type="string", example="123456"),
 *     @OA\Property(property="card_code", type="string", example="ABC-987"),
 *     @OA\Property(property="full_name", type="string", example="Nguyễn Văn A"),
 *     @OA\Property(property="birth_date", type="string", format="date", example="1990-05-20"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *     @OA\Property(property="phone", type="string", example="0987654321"),
 *     @OA\Property(property="email", type="string", format="email", example="nguyenvana@example.com"),
 *     @OA\Property(property="unit_name", type="string", example="Công ty ABC"),
 *     @OA\Property(property="unit_position", type="string", example="Giám đốc"),
 *     @OA\Property(property="association_position", type="string", example="Thành viên"),
 *     @OA\Property(property="term", type="integer", example=2024),
 *     @OA\Property(property="attendance_permission", type="boolean", example=true),
 *     @OA\Property(property="avatar", type="string", format="binary"),
 * )
 */
class StoreBoardCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login_code' => 'required|string|max:255|unique:board_customers',
            'card_code' => 'required|string|max:255|unique:board_customers',
            'full_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'unit_name' => 'required|string|max:255',
            'unit_position' => 'required|string|max:255',
            'association_position' => 'required|string|max:255',
            'term' => 'required|integer|min:1900|max:2100',
            'attendance_permission' => 'nullable|boolean',
            'avatar' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'login_code.required' => 'Mã đăng nhập là bắt buộc.',
            'login_code.unique' => 'Mã đăng nhập phải là duy nhất.',
            'card_code.required' => 'Mã thẻ là bắt buộc.',
            'card_code.unique' => 'Mã thẻ phải là duy nhất.',
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'birth_date.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính phải là nam, nữ, hoặc khác.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email phải là hợp lệ.',
            'unit_name.required' => 'Tên đơn vị là bắt buộc.',
            'unit_position.required' => 'Chức vụ trong đơn vị là bắt buộc.',
            'association_position.required' => 'Chức vụ trong hiệp hội là bắt buộc.',
            'term.required' => 'Nhiệm kỳ là bắt buộc.',
            'term.integer' => 'Nhiệm kỳ phải là một số nguyên.',
            'term.min' => 'Nhiệm kỳ phải lớn hơn hoặc bằng 1900.',
            'term.max' => 'Nhiệm kỳ phải nhỏ hơn hoặc bằng 2100.',
            'attendance_permission.boolean' => 'Quyền tham dự phải là đúng hoặc sai.',
            'avatar.string' => 'Ảnh đại diện phải là một chuỗi hợp lệ.',
        ];
    }
}
