<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        $employeeRole = DB::table('roles')->where('name', 'Employee')->first();

        if (!$adminRole || !$employeeRole) return;

        // Admin gets ALL permissions
        $allPermissions = DB::table('permissions')->get();
        foreach ($allPermissions as $perm) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $adminRole->id, 'permission_id' => $perm->id],
                ['role_id' => $adminRole->id, 'permission_id' => $perm->id]
            );
        }

        // Employee gets only READ permissions
        $readPermissions = DB::table('permissions')->where('name', 'read')->get();
        foreach ($readPermissions as $perm) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $employeeRole->id, 'permission_id' => $perm->id],
                ['role_id' => $employeeRole->id, 'permission_id' => $perm->id]
            );
        }

        // ZoneManager, BranchManager, LocationManager get: read, create, edit, approve (no delete)
        $managerRoles = DB::table('roles')->whereIn('name', ['ZoneManager', 'BranchManager', 'LocationManager'])->get();
        $managerActions = ['read', 'create', 'edit', 'approve'];
        $managerPermissions = DB::table('permissions')->whereIn('name', $managerActions)->get();

        foreach ($managerRoles as $role) {
            foreach ($managerPermissions as $perm) {
                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $role->id, 'permission_id' => $perm->id],
                    ['role_id' => $role->id, 'permission_id' => $perm->id]
                );
            }
        }
    }

    public function down(): void {
        DB::table('role_permissions')->truncate();
    }
};
