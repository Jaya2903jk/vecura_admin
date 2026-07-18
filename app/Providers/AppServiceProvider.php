<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\NewBranch;
use App\Models\IssueDepartment;
use App\Models\Designation;
use App\Models\Role;
use App\Observers\NewBranchObserver;
use App\Observers\IssueDepartmentObserver;
use App\Observers\DesignationMasterObserver;
use App\Observers\RoleObserver;
use App\Repositories\PatientRepositoryInterface;
use App\Repositories\PatientRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);
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

        // Register model observers for auto-cache clearing
        NewBranch::observe(NewBranchObserver::class);
        IssueDepartment::observe(IssueDepartmentObserver::class);
        Designation::observe(DesignationMasterObserver::class);
        Role::observe(RoleObserver::class);
    }
}
