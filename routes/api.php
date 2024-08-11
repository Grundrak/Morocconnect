<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware(['auth:api', CheckRole::class . ':admin'])->group(function () {
    Route::post('/assign-role/{user}', [RoleController::class, 'assignRole']);
    Route::get('/user/{user}/roles', [RoleController::class, 'getUserRoles']);
});
