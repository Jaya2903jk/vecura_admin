<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Only allows admin users
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('user_id')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!session('is_admin')) {
            return response()->json(['status' => false, 'message' => 'Admin access required'], 403);
        }

        return $next($request);
    }
}
