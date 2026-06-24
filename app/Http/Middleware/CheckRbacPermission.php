<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RbacService;

class CheckRbacPermission
{
    public function __construct(private RbacService $rbac) {}

    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/')->with('error', 'Unauthorized');

        foreach ($permissions as $permission) {
            [$perm, $module] = explode(':', $permission) + [null, null];
            if ($this->rbac->hasPermission($userId, $perm, $module)) return $next($request);
        }

        return redirect()->back()->with('error', 'Permission denied');
    }
}
