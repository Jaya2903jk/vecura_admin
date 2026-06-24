<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Module;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = Module::where('is_active', 1)->pluck('name');
        $actions = ['read', 'create', 'edit', 'delete', 'approve'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::updateOrInsert(
                    ['name' => $action, 'module' => $module],
                    [
                        'description' => "$action on $module",
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}
