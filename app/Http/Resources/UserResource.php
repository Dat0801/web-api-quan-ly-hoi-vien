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
 *     @OA\Property(property="phoneNumber", type="string", example="0123456789", description="Số điện thoại của người dùng."),
 *     @OA\Property(
 *         property="role",
 *         type="object",
 *         description="Thông tin vai trò của người dùng",
 *         @OA\Property(property="id", type="integer", example=2, description="ID vai trò của người dùng."),
 *         @OA\Property(property="roleCode", type="string", example="USER001", description="Mã vai trò của người dùng."),
 *         @OA\Property(property="roleName", type="string", example="User", description="Tên vai trò của người dùng.")
 *     ),
 *     @OA\Property(property="status", type="boolean", example=true, description="Trạng thái tài khoản của người dùng (true: hoạt động, false: bị khóa)."),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://example.com/avatar.jpg", description="Đường dẫn ảnh đại diện của người dùng."),
 *     @OA\Property(property="lastLogin", type="string", format="date-time", nullable=true, example="2024-02-17 12:30:45", description="Thời gian đăng nhập lần cuối của người dùng.")
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
            'phoneNumber' => $this->phone_number,
            'role' => new RoleResource($this->role),
            'status' => $this->status,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'lastLogin' => $this->last_login ? Carbon::parse($this->last_login)->format('Y-m-d H:i:s') : null,
        ];
    }
}
