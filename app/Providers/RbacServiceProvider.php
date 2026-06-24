<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RbacService;

class RbacServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RbacService::class, function ($app) {
            return new RbacService();
        });
    }

    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('check.permission', \App\Http\Middleware\CheckRbacPermission::class);
        $this->app['router']->aliasMiddleware('check.role', \App\Http\Middleware\CheckRole::class);
    }
}
