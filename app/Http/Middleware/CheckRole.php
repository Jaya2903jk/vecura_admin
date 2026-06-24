<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RbacService;

class CheckRole
{
    public function __construct(private RbacService $rbac) {}

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) return redirect('/')->with('error', 'Unauthorized');

        $userRoles = $this->rbac->getEmployeeRoles($user->UserID)->pluck('role.name')->toArray();

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) return $next($request);
        }

        return redirect()->back()->with('error', 'Insufficient role');
    }
}
