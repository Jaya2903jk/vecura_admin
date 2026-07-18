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
        // Cache sidebar modules for 1 hour (3600 seconds) - set to 0 for no cache during development
        $cacheTtl = env('MODULE_CACHE_TTL', 3600);

        View::composer('layout.partials.sidebar', function ($view) use ($cacheTtl) {
            if ($cacheTtl === 0) {
                // No caching in development
                $moduleGroups = Module::whereNotNull('parent')
                    ->where('is_active', 1)
                    ->orderBy('parent')
                    ->orderBy('sort_order')
                    ->get()
                    ->groupBy('parent');
            } else {
                // Cache for specified TTL (in seconds)
                $moduleGroups = Cache::remember('sidebar_modules', $cacheTtl, function () {
                    return Module::whereNotNull('parent')
                        ->where('is_active', 1)
                        ->orderBy('parent')
                        ->orderBy('sort_order')
                        ->get()
                        ->groupBy('parent');
                });
            }

            $view->with('moduleGroups', $moduleGroups);
        });
    }
}
