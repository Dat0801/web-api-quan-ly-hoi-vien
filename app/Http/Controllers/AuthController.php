<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponse;
/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API xác thực người dùng"
 * )
 */
class AuthController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Đăng nhập và lấy token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="nguyenbin394@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng nhập thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|asdhjkashdkjashdkjashdkj"),
     *                 @OA\Property(property="expires_at", type="string", example="2025-02-17 14:00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="error", example="success"),
     *            @OA\Property(property="message", type="string", example="Unauthorized"),
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Unauthorized', 401);
        }

        $token = $user->createToken('Admin Dashboard')->plainTextToken;

        $user->tokens()->latest()->first()->update([
            'expires_at' => now()->addHours(4),
        ]);

        return $this->success([
            'token' => $token,
            'expires_at' => now()->addHours(4),
        ], 'Login successful');
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Đăng xuất",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Đăng xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success(null, 'Logged out successfully');
    }
}
