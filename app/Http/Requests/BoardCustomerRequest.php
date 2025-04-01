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
class BoardCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login_code' => 'required|unique:board_customers',
            'card_code' => 'required|unique:board_customers',
            'full_name' => 'required',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required',
            'email' => 'required|email',
            'unit_name' => 'required',
            'unit_position' => 'required',
            'association_position' => 'required',
            'term' => 'required|integer',
            'attendance_permission' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages() {
        return [
            'login_code.required' => 'Mã đăng nhập là bắt buộc.',
            'login_code.unique' => 'Mã đăng nhập phải là duy nhất.',
            'card_code.required' => 'Mã thẻ là bắt buộc.',
            'card_code.unique' => 'Mã thẻ phải là duy nhất.',
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'birth_date.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính phải là một trong các giá trị: nam, nữ, hoặc khác.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email phải là một email hợp lệ.',
            'unit_name.required' => 'Tên đơn vị là bắt buộc.',
            'unit_position.required' => 'Chức vụ trong đơn vị là bắt buộc.',
            'association_position.required' => 'Chức vụ trong hiệp hội là bắt buộc.',
            'term.required' => 'Nhiệm kỳ là bắt buộc.',
            'term.integer' => 'Nhiệm kỳ phải là một số nguyên.',
            'attendance_permission.boolean' => 'Quyền tham dự phải là đúng hoặc sai.',
            'avatar.image' => 'Ảnh đại diện phải là một hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg, gif, svg.',
            'avatar.max' => 'Ảnh đại diện không được lớn hơn 2048 kilobytes.',
        ];
    }
}
