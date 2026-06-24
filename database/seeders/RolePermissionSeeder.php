<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'Admin')->first();
        $allPermissions = Permission::pluck('id');
        if ($admin) $admin->permissions()->syncWithoutDetaching($allPermissions);

        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $readPerms = Permission::where('name', 'read')->pluck('id');
            $employeeRole->permissions()->syncWithoutDetaching($readPerms);
        }
    }
}
