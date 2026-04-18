<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMaster;
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
                    $q->where('UserID', (int)$request->login);
                } else {
                    $q->where('UserCode', $request->login);
                }
            })
            ->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($request->password !== $user->Password) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        session([
            'user_id'   => $user->UserID,
            'user_name' => $user->FullName ?? $user->UserName,
            'role_id'   => $user->userGroup->UserGroupID ?? null,
            'role_name' => $user->userGroup->UserGroupName ?? null,
        ]);

        return response()->json([
            'status' => true,
            'redirect' => url('/dashboard')
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
