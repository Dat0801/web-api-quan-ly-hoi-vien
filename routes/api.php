<?php

use App\Http\Controllers\Category\BusinessController;
use App\Http\Controllers\Category\CertificateController;
use App\Http\Controllers\Category\FieldController;
use App\Http\Controllers\Category\IndustryController;
use App\Http\Controllers\Category\MarketController;
use App\Http\Controllers\Category\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Resources\UserResource;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Category\TargetCustomerGroupController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum', 'token.expiration'])->group(function () {

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::post('/', [DocumentController::class, 'store']);
        Route::delete('/{id}', [DocumentController::class, 'destroy']);
        Route::get('{id}/download', [DocumentController::class, 'download']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [AccountController::class, 'index']);
        Route::post('/', [AccountController::class, 'store']);
        Route::get('/{id}', [AccountController::class, 'show']);
        Route::put('/{id}', [AccountController::class, 'update']);
        Route::delete('/{id}', [AccountController::class, 'destroy']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    Route::prefix('markets')->group(function () {
        Route::get('/', [MarketController::class, 'index']);
        Route::post('/', [MarketController::class, 'store']);
        Route::get('/{id}', [MarketController::class, 'show']);
        Route::put('/{id}', [MarketController::class, 'update']);
        Route::delete('/{id}', [MarketController::class, 'destroy']);
    });

    Route::prefix('industries')->group(function () {
        Route::get('/', [IndustryController::class, 'index']);
        Route::post('/', [IndustryController::class, 'store']);
        Route::get('/{id}', [IndustryController::class, 'show']);
        Route::put('/{id}', [IndustryController::class, 'update']);
        Route::delete('/{id}', [IndustryController::class, 'destroy']);
    });

    Route::prefix('fields')->group(callback: function () {
        Route::get('/', [FieldController::class, 'index']);
        Route::post('/', [FieldController::class, 'store']);
        Route::get('/{id}', [FieldController::class, 'show']);
        Route::put('/{id}', [FieldController::class, 'update']);
        Route::delete('/{id}', [FieldController::class, 'destroy']);
    });

    Route::prefix('businesses')->group(callback: function () {
        Route::get('/', [BusinessController::class, 'index']);
        Route::post('/', [BusinessController::class, 'store']);
        Route::get('/{id}', [BusinessController::class, 'show']);
        Route::put('/{id}', [BusinessController::class, 'update']);
        Route::delete('/{id}', [BusinessController::class, 'destroy']);
    });

    Route::prefix('certificates')->group(callback: function () {
        Route::get('/', [CertificateController::class, 'index']);
        Route::post('/', [CertificateController::class, 'store']);
        Route::get('/{id}', [CertificateController::class, 'show']);
        Route::put('/{id}', [CertificateController::class, 'update']);
        Route::delete('/{id}', [CertificateController::class, 'destroy']);
    });

    Route::prefix('organizations')->group(callback: function () {
        Route::get('/', [OrganizationController::class, 'index']);
        Route::post('/', [OrganizationController::class, 'store']);
        Route::get('/{id}', [OrganizationController::class, 'show']);
        Route::put('/{id}', [OrganizationController::class, 'update']);
        Route::delete('/{id}', [OrganizationController::class, 'destroy']);
    });

    Route::prefix('target-customer-groups')->group(callback: function () {
        Route::get('/', [TargetCustomerGroupController::class, 'index']);
        Route::post('/', [TargetCustomerGroupController::class, 'store']);
        Route::get('/{id}', [TargetCustomerGroupController::class, 'show']);
        Route::put('/{id}', [TargetCustomerGroupController::class, 'update']);
        Route::delete('/{id}', [TargetCustomerGroupController::class, 'destroy']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

