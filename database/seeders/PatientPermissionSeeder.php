<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Permission;

class PatientPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update patient module
        $module = Module::updateOrInsert(
            ['name' => 'patient'],
            [
                'name' => 'patient',
                'parent' => 'Masters',
                'description' => 'Patient Management',
                'icon' => 'ti ti-user-check',
                'route_prefix' => 'patient',
                'sort_order' => 0,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create permissions for patient module
        $actions = ['read', 'create', 'edit', 'delete'];
        foreach ($actions as $action) {
            Permission::updateOrInsert(
                ['name' => $action, 'module' => 'patient'],
                [
                    'description' => "$action on patient records",
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        echo "✓ Patient module and permissions created successfully!\n";
    }
}
