<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BadgeController;

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


Route::middleware('auth:api')->group(function () {
    // Badge routes
    Route::apiResource('badges', BadgeController::class);
    Route::post('users/{user}/award-badge', [BadgeController::class, 'awardBadge']);
    Route::post('users/{user}/remove-badge', [BadgeController::class, 'removeBadge']);
    Route::post('check-badges', [BadgeController::class, 'checkAndAwardBadges']);
});

Route::middleware('auth:api')->group(function () {
// Poste routes
    Route::apiResource('posts', PostController::class);
    Route::post('posts/{post}/like', [PostController::class, 'like']);
    Route::post('posts/{post}/share', [PostController::class, 'share']);
    Route::post('posts/{post}/request-verification', [PostController::class, 'requestVerification']);
    Route::post('posts/{post}/verify', [PostController::class, 'verify']);
    Route::post('posts/{post}/reject-verification', [PostController::class, 'rejectVerification']);
});