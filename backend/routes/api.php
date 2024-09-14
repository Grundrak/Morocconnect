<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;

// Public routes

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    // Post routes
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('post', PostController::class);
        Route::post('post/{post}/like', [PostController::class, 'like']);
        Route::post('post/{post}/unlike', [PostController::class, 'unlike']);
        Route::post('post/{post}/share', [PostController::class, 'share']);
        Route::post('post/{post}/request-verification', [PostController::class, 'requestVerification']);
    });

    // Comment routes 
    Route::middleware('auth:api')->group(function () {
        Route::post('post/{post}/comments', [CommentController::class, 'store']);
        Route::get('post/{post}/comments', [CommentController::class, 'index']);
        Route::put('comments/{comment}', [CommentController::class, 'update']);
        Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    });

    //follow routes 
    Route::middleware('auth:api')->group(function () {
        Route::post('users/{user}/follow', [FollowController::class, 'follow']);
        Route::post('users/{user}/unfollow', [FollowController::class, 'unfollow']);
        Route::get('users/{user}/followers', [FollowController::class, 'followers']);
        Route::get('users/{user}/following', [FollowController::class, 'following']);
    });


    // Badge routes
    Route::get('badges', [BadgeController::class, 'index']);
    Route::get('badges/{badge}', [BadgeController::class, 'show']);
    Route::post('check-badges', [BadgeController::class, 'checkAndAwardBadges']);

    // User routes
    Route::get('users/{user}/roles', [RoleController::class, 'getUserRoles']);
});

// Admin routes
Route::middleware(['auth:api', CheckRole::class . ':admin'])->group(function () {
    // Role management
    Route::post('assign-role/{user}', [RoleController::class, 'assignRole']);

    // Badge management
    Route::post('badges', [BadgeController::class, 'store']);
    Route::put('badges/{badge}', [BadgeController::class, 'update']);
    Route::delete('badges/{badge}', [BadgeController::class, 'destroy']);
    Route::post('users/{user}/award-badge', [BadgeController::class, 'awardBadge']);
    Route::post('users/{user}/remove-badge', [BadgeController::class, 'removeBadge']);

    // Post verification
    Route::post('posts/{post}/verify', [PostController::class, 'verify']);
    Route::post('posts/{post}/reject-verification', [PostController::class, 'rejectVerification']);
});
