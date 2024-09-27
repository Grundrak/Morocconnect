<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;


Route::middleware(['cors'])->group(function () {

    // Public routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('user', function (Request $request) {
            return $request->user();
        });

        // Search route
        Route::get('search', [SearchController::class, 'search']);

        // User routes
        Route::get('users/suggested', [AuthController::class, 'suggestedUsers']);
        Route::get('users/{id}', [AuthController::class, 'show']);
        Route::put('users/profile', [AuthController::class, 'update']);
        Route::post('users/avatar', [AuthController::class, 'updateAvatar']);
        Route::get('users/{user}/roles', [RoleController::class, 'getUserRoles']);

        // Post routes
        Route::apiResource('post', PostController::class);
        Route::post('post/{post}/like', [PostController::class, 'like']);
        Route::post('post/{post}/unlike', [PostController::class, 'unlike']);
        Route::post('post/{post}/share', [PostController::class, 'share']);
        Route::post('post/{post}/request-verification', [PostController::class, 'requestVerification']);

        // Comment routes 
        Route::post('post/{post}/comments', [CommentController::class, 'store']);
        Route::get('post/{post}/comments', [CommentController::class, 'index']);
        Route::put('comments/{comment}', [CommentController::class, 'update']);
        Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

        // Follow routes 
        Route::post('users/{user}/follow', [FollowController::class, 'follow']);
        Route::post('users/{user}/unfollow', [FollowController::class, 'unfollow']);
        Route::get('users/{user}/followers', [FollowController::class, 'followers']);
        Route::get('users/{user}/following', [FollowController::class, 'following']);

        // Media upload
        Route::post('media/upload', [MediaController::class, 'upload']);

        // Badge routes
        Route::get('badges', [BadgeController::class, 'index']);
        Route::get('badges/{badge}', [BadgeController::class, 'show']);
        Route::post('check-badges', [BadgeController::class, 'checkAndAwardBadges']);

        // Notification routes
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    // Admin routes
    Route::middleware(['auth:api', CheckRole::class . ':admin'])->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::post('users/{user}/assign-role', [RoleController::class, 'assignRole']);
        Route::get('admin/dashboard-data', [AdminController::class, 'dashboardData']);
        Route::get('admin/users', [AdminController::class, 'users']);
        Route::get('admin/posts', [AdminController::class, 'posts']);
        Route::get('admin/comments', [AdminController::class, 'comments']);
        Route::put('admin/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
        Route::post('users/{user}/award-badge', [BadgeController::class, 'awardBadge']);
        Route::post('users/{user}/remove-badge', [BadgeController::class, 'removeBadge']);
    });
});
