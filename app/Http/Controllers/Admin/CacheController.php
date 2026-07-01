<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.custom');
    }

    public function modules()
    {
        $modules = Module::where('is_active', 1)
            ->orderBy('parent')
            ->orderBy('sort_order')
            ->paginate(20);

        $cacheStatus = [
            'sidebar_modules' => Cache::has('sidebar_modules'),
            'cached_at' => Cache::get('sidebar_modules_cached_at'),
        ];

        return view('admin.cache-modules', compact('modules', 'cacheStatus'));
    }

    public function clearSidebarCache()
    {
        Cache::forget('sidebar_modules');
        Cache::forget('sidebar_modules_cached_at');

        return redirect()->route('admin.cache.modules')
            ->with('success', '✅ Sidebar cache cleared successfully!');
    }

    public function clearAllCache()
    {
        // Clear specific caches
        Cache::forget('sidebar_modules');
        Cache::forget('sidebar_modules_cached_at');

        // Clear application cache
        Cache::flush();

        return redirect()->route('admin.cache.modules')
            ->with('success', '✅ All application cache cleared successfully!');
    }

    public function rebuildModuleCache()
    {
        Cache::forget('sidebar_modules');

        // Rebuild cache
        $moduleGroups = Module::whereNotNull('parent')
            ->where('is_active', 1)
            ->orderBy('parent')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('parent');

        Cache::put('sidebar_modules', $moduleGroups, 3600);
        Cache::put('sidebar_modules_cached_at', now(), 3600);

        return redirect()->route('admin.cache.modules')
            ->with('success', '✅ Module cache rebuilt successfully!');
    }

    public function toggleModuleStatus(Module $module)
    {
        $module->update(['is_active' => !$module->is_active]);
        Cache::forget('sidebar_modules');

        return back()->with('success', 'Module status updated. Cache cleared.');
    }
}
