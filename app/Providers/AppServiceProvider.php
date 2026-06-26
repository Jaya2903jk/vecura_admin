<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrap();

        // Register custom middleware alias
        $router = $this->app->make('router');
        $router->aliasMiddleware('check.permission', \App\Http\Middleware\CheckPermission::class);
        $router->aliasMiddleware('admin.only', \App\Http\Middleware\AdminOnly::class);

        // Share master module names with all views
        \Illuminate\Support\Facades\View::share('masterModuleNames', [
            'zone', 'branch', 'location', 'department', 'designation',
            'country', 'state', 'city', 'category', 'staff'
        ]);

        // Register Blade helpers for RBAC
        \Illuminate\Support\Facades\Blade::if('hasPermission', function ($permissionName, $module = null) {
            return \App\Helpers\RbacHelper::hasPermission($permissionName, $module);
        });

        \Illuminate\Support\Facades\Blade::if('hasRole', function ($roleName) {
            return \App\Helpers\RbacHelper::hasRole($roleName);
        });

        \Illuminate\Support\Facades\Blade::if('isAdmin', function () {
            return \App\Helpers\RbacHelper::isAdmin();
        });
    }
}
