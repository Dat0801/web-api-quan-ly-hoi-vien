<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="Thông tin người dùng",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Nguyễn Văn A"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="phoneNumber", type="string", example="0123456789"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://example.com/avatar.jpg"),
 *     @OA\Property(property="lastLogin", type="string", format="date-time", nullable=true, example="2024-02-17 12:30:45"),
 *     @OA\Property(
 *         property="role",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=2),
 *         @OA\Property(property="roleCode", type="string", example="USER001"),
 *         @OA\Property(property="roleName", type="string", example="User")
 *     )
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
