<?php

namespace App\Http\Controllers;

use App\Models\UserMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        // $user = UserMaster::with('userGroup')
        //     ->where(function ($q) use ($request) {
        //         $q->where('UserID', $request->login)
        //             ->orWhere('UserCode', $request->login);
        //     })
        //     ->first();
        $user = UserMaster::with('userGroup')
            ->where(function ($q) use ($request) {
                if (is_numeric($request->login)) {
                    $q->where('UserID', (int) $request->login);
                } else {
                    $q->where('UserCode', $request->login);
                }
            })
            ->first();
        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        if (!Hash::check($request->password, $user->Password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Load user with RBAC roles and hierarchy
        $userWithRbac = $user->load([
            'roles' => function ($q) {
                $q->where('roles.is_active', 1)->orderBy('level');
            },
            'departments',
            'hierarchyAccess'
        ]);

        // Get primary RBAC role (lowest level number = highest priority)
        $rbacRole = $userWithRbac->roles->first();

        session([
            'user_id' => $user->UserID,
            'user_name' => $user->FullName ?? $user->UserName,
            'role_id' => $user->userGroup->UserGroupID ?? null,
            'role_name' => $user->userGroup->UserGroupName ?? null,
            // New RBAC session data
            'rbac_role_id' => $rbacRole->id ?? null,
            'rbac_role_name' => $rbacRole->name ?? 'Employee',
            'rbac_roles' => $userWithRbac->roles->pluck('name')->toArray(),
            'is_admin' => $rbacRole && $rbacRole->name === 'Admin',
        ]);

        return response()->json([
            'status' => true,
            'redirect' => url('/dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        session()->forget('user_id');
        session()->flush();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
