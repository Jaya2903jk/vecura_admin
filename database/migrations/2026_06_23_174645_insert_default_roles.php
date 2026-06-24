<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $roles = [
            ['name' => 'Admin', 'level' => 1, 'description' => 'System Administrator with full access', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ZoneManager', 'level' => 2, 'description' => 'Manages zone and subordinate branches/locations', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BranchManager', 'level' => 3, 'description' => 'Manages branch and subordinate locations', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LocationManager', 'level' => 4, 'description' => 'Manages location and department staff', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Employee', 'level' => 5, 'description' => 'Regular employee with read-only access', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['name' => $role['name']], $role);
        }
    }

    public function down(): void {
        DB::table('roles')->whereIn('name', ['Admin', 'ZoneManager', 'BranchManager', 'LocationManager', 'Employee'])->delete();
    }
};
