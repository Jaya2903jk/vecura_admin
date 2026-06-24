<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(['name' => 'Admin'], ['level' => 1, 'description' => 'System Administrator', 'is_active' => 1]);
        Role::updateOrCreate(['name' => 'ZoneManager'], ['level' => 2, 'description' => 'Zone Manager', 'is_active' => 1]);
        Role::updateOrCreate(['name' => 'BranchManager'], ['level' => 3, 'description' => 'Branch Manager', 'is_active' => 1]);
        Role::updateOrCreate(['name' => 'LocationManager'], ['level' => 4, 'description' => 'Location Manager', 'is_active' => 1]);
        Role::updateOrCreate(['name' => 'Employee'], ['level' => 5, 'description' => 'Employee', 'is_active' => 1]);
    }
}
