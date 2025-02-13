<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

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
            'expiresAt' => now()->addHours(4),
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success(null, 'Logged out successfully');
    }
}
