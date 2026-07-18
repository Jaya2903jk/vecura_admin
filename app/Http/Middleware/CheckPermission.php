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

        // Allow admin users
        if (session('is_admin')) {
            return $next($request);
        }

        if (!RbacHelper::hasPermission($permissionName, $module)) {
            // For API requests, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Permission denied'
                ], 403);
            }
            // For web requests, show permission denied page
            return response()->view('errors.permission-denied', [], 403);
        }

        return $next($request);
    }
}
