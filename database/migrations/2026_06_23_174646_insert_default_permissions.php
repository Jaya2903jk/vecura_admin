<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $modules = ['department', 'designation', 'branch', 'location', 'country', 'zone', 'state', 'city', 'employee', 'ticket', 'iou', 'pcrequest'];
        $actions = ['read', 'create', 'edit', 'delete', 'approve'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $action, 'module' => $module],
                    [
                        'description' => "$action on $module",
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void {
        DB::table('permissions')->truncate();
    }
};
