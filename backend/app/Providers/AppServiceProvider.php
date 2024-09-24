<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\Comment;
use App\Policies\CommentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Comment::class, CommentPolicy::class);

        // Create a symbolic link from public/storage to storage/app/public
        if (!file_exists(public_path('storage'))) {
            app('files')->link(storage_path('app/public'), public_path('storage'));
        }

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}