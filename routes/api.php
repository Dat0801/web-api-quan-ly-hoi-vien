<?php

use App\Http\Controllers\Category\BusinessController;
use App\Http\Controllers\Category\CertificateController;
use App\Http\Controllers\Category\FieldController;
use App\Http\Controllers\Category\IndustryController;
use App\Http\Controllers\Category\MarketController;
use App\Http\Controllers\Category\OrganizationController;
use App\Http\Controllers\Customer\BoardCustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
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

    Route::resource('documents', DocumentController::class, [
        'only' => ['index', 'store', 'destroy'],
    ]);
    Route::get('/documents/{id}/download', [DocumentController::class, 'download']);

    Route::resource('users', AccountController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('roles', RoleController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('markets', MarketController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('industries', IndustryController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('fields', FieldController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('businesses', BusinessController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('certificates', CertificateController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('organizations', OrganizationController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('target-customer-groups', TargetCustomerGroupController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::resource('board-customers', BoardCustomerController::class, [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
    ]);

    Route::post('/logout', [AuthController::class, 'logout']);
});

