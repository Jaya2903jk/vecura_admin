<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateStaffPermissions extends Seeder
{
    public function run(): void
    {
        // Insert permissions for staff module
        $permissions = [
            [
                'name' => 'read',
                'module' => 'staff',
                'description' => 'read on staff',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'create',
                'module' => 'staff',
                'description' => 'create on staff',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'edit',
                'module' => 'staff',
                'description' => 'edit on staff',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'delete',
                'module' => 'staff',
                'description' => 'delete on staff',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'approve',
                'module' => 'staff',
                'description' => 'approve on staff',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert permissions (ignore if already exist)
        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name'], 'module' => $permission['module']],
                $permission
            );
        }

        echo "✓ Staff permissions created successfully!\n";
    }
}
