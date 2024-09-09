<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(LaravelServiceProvider::class);
    }
}