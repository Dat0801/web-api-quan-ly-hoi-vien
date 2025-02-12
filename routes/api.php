<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Resources\UserResource;
use App\Http\Controllers\User\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Đăng nhập (không cần auth)
Route::post('/login', [AuthController::class, 'login']);

// Các route yêu cầu xác thực
Route::middleware(['auth:sanctum', 'token.expiration'])->group(function () {
    
    // Lấy thông tin user đang đăng nhập
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    // Quản lý Documents
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::post('/', [DocumentController::class, 'store']);
        Route::delete('/{id}', [DocumentController::class, 'destroy']);
        Route::get('/download/{id}', [DocumentController::class, 'download']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [AccountController::class, 'index']); // Lấy danh sách user
        Route::post('/', [AccountController::class, 'store']); // Tạo user mới
        Route::get('/{account}', [AccountController::class, 'show']); // Lấy thông tin user
        Route::put('/{account}', [AccountController::class, 'update']); // Cập nhật user
        Route::delete('/{account}', [AccountController::class, 'destroy']); // Xóa user
    });

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout']);
});

