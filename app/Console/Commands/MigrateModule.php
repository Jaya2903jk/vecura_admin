<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateModule extends Command
{
    protected $signature = 'migrate:module {module? : Module name (rbac, master, tickets, iou, pc, hr, etc.)}';
    protected $description = 'Migrate specific modules only';

    public function handle()
    {
        $module = $this->argument('module');

        if (!$module) {
            $this->showModules();
            return;
        }

        $patterns = [
            'rbac' => ['role', 'permission', 'employee_role', 'employee_department', 'hierarchy_access'],
            'master' => ['country', 'zone', 'state', 'city', 'branch', 'location', 'issue_department', 'designation'],
            'tickets' => ['issue_categor', 'issue_master', 'issue_ticket', 'facility_', 'approval_flow', 'complaint_'],
            'iou' => ['iou_request', 'iou_settlement', 'iou_settlement_item'],
            'pc' => ['pc_request', 'pc_bill_submission', 'pc_bill_item'],
            'hr' => ['hr_manpower', 'hr_ticket'],
            'machine' => ['machine_table', 'machine_issue'],
            'biomedical' => ['biomedical_ticket'],
            'wallet' => ['wallet_transaction', 'branch_wallet'],
            'money' => ['money_transaction', 'claim_request'],
            'notification' => ['notification'],
            'expense' => ['expense_master', 'employee_balance'],
        ];

        if (!isset($patterns[$module])) {
            $this->error("Module '$module' not found!");
            $this->showModules();
            return;
        }

        $this->info("Migrating $module...");

        foreach ($patterns[$module] as $pattern) {
            $files = glob(database_path("migrations/*$pattern*.php"));
            foreach ($files as $file) {
                $this->info("  → " . basename($file));
            }
        }

        Artisan::call('migrate');
        $this->info("✅ $module migration complete!");
    }

    protected function showModules()
    {
        $this->info("Available modules:");
        $this->line("  php artisan migrate:module rbac");
        $this->line("  php artisan migrate:module master");
        $this->line("  php artisan migrate:module tickets");
        $this->line("  php artisan migrate:module iou");
        $this->line("  php artisan migrate:module pc");
        $this->line("  php artisan migrate:module hr");
        $this->line("  php artisan migrate:module machine");
        $this->line("  php artisan migrate:module biomedical");
        $this->line("  php artisan migrate:module wallet");
        $this->line("  php artisan migrate:module money");
        $this->line("  php artisan migrate:module notification");
        $this->line("  php artisan migrate:module expense");
    }
}
