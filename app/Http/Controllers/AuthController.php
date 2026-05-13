<?php

namespace App\Http\Controllers;

use App\Models\UserMaster;
use Illuminate\Http\Request;

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

        if ($request->password !== $user->Password) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        session([
            'user_id' => $user->UserID,
            'user_name' => $user->FullName ?? $user->UserName,
            'role_id' => $user->userGroup->UserGroupID ?? null,
            'role_name' => $user->userGroup->UserGroupName ?? null,
        ]);
        $redirect = '/dashboard';
        $roleName = strtolower(
            trim($user->userGroup->UserGroupName ?? '')
        );
        // STAFF ROLE
        $staffRoles = [

            'centre / branch',
            'warehouse',
            'consultant / doctor',
            'branch manager',
            'call centre',
            'accounts',
            'corporate',
        ];

        if (in_array($roleName, $staffRoles)) {

            $redirect = '/staff-dashboard';
        }

        return response()->json([
            'status' => true,
            'redirect' => url($redirect),
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
