<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="Thông tin người dùng",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của người dùng."),
 *     @OA\Property(property="name", type="string", example="Nguyễn Văn A", description="Tên đầy đủ của người dùng."),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Địa chỉ email của người dùng."),
 *     @OA\Property(property="phone_number", type="string", example="0123456789", description="Số điện thoại của người dùng."),
 *     @OA\Property(property="role_id", type="integer", example=2, description="ID của vai trò người dùng."),
 *     @OA\Property(property="status", type="boolean", example=true, description="Trạng thái tài khoản của người dùng (true: hoạt động, false: bị khóa)."),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://example.com/avatar.jpg", description="Đường dẫn ảnh đại diện của người dùng."),
 *     @OA\Property(property="last_login", type="string", format="date-time", nullable=true, example="2024-02-17 12:30:45", description="Thời gian đăng nhập lần cuối của người dùng."),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-17 12:30:45", description="Thời gian tạo tài khoản."),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-17 12:30:45", description="Thời gian cập nhật tài khoản."),
 * )
 */

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'role_id' => $this->role_id,
            'status' => $this->status,
            'avatar' => $this->avatar ?? null,
            'last_login' => $this->last_login ? Carbon::parse($this->last_login)->format('Y-m-d H:i:s') : null,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
