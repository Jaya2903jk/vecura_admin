<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\RbacHelper;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('check.permission:read,department')
     */
    public function handle(Request $request, Closure $next, $permissionName, $module = null)
    {
        if (!session('user_id')) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        if (!RbacHelper::hasPermission($permissionName, $module)) {
            return redirect()->back()->with('error', 'Permission denied: ' . $permissionName . ':' . ($module ?? 'all'));
        }

        return $next($request);
    }
}
