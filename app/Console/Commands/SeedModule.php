<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedModule extends Command
{
    protected $signature = 'seed:module {module? : Module name (rbac, roles, permissions, etc.)}';
    protected $description = 'Seed specific modules only';

    public function handle()
    {
        $module = $this->argument('module');

        if (!$module) {
            $this->showModules();
            return;
        }

        $seeders = [
            'rbac' => ['RoleSeeder', 'PermissionSeeder', 'RolePermissionSeeder'],
            'roles' => ['RoleSeeder'],
            'permissions' => ['PermissionSeeder'],
            'role-permissions' => ['RolePermissionSeeder'],
        ];

        if (!isset($seeders[$module])) {
            $this->error("Module '$module' not found!");
            $this->showModules();
            return;
        }

        $this->info("Seeding $module...");

        foreach ($seeders[$module] as $seeder) {
            $this->info("  → Running $seeder");
            Artisan::call("db:seed --class=$seeder");
        }

        $this->info("✅ $module seeding complete!");
    }

    protected function showModules()
    {
        $this->info("Available seeders:");
        $this->line("  php artisan seed:module rbac              (All RBAC seeders)");
        $this->line("  php artisan seed:module roles             (RoleSeeder only)");
        $this->line("  php artisan seed:module permissions       (PermissionSeeder only)");
        $this->line("  php artisan seed:module role-permissions  (RolePermissionSeeder only)");
    }
}
