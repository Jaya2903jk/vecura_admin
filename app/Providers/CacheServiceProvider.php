<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Module;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Cache sidebar modules for 1 hour (3600 seconds)
        View::composer('layout.partials.sidebar', function ($view) {
            $moduleGroups = Cache::remember('sidebar_modules', 3600, function () {
                return Module::whereNotNull('parent')
                    ->where('is_active', 1)
                    ->orderBy('parent')
                    ->orderBy('sort_order')
                    ->get()
                    ->groupBy('parent');
            });

            $view->with('moduleGroups', $moduleGroups);
        });
    }
}
