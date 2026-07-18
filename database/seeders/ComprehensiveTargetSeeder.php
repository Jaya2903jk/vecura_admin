<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Seeder;

class ComprehensiveTargetSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // MONTHLY TARGETS FOR EMPLOYEES - JUNE 2026
        // ============================================================

        $branches = [
            ['id' => 1, 'name' => 'PORUR', 'employee' => 1001, 'target' => 1500000],
            ['id' => 2, 'name' => 'T NAGAR', 'employee' => 1002, 'target' => 1600000],
            ['id' => 3, 'name' => 'OMR', 'employee' => 1003, 'target' => 1300000],
            ['id' => 4, 'name' => 'MADURAI', 'employee' => 1004, 'target' => 1200000],
        ];

        // Monthly Targets (Month-wise)
        foreach ($branches as $branch) {
            Target::create([
                'user_id' => $branch['employee'],
                'branch_id' => $branch['id'],
                'target_type' => 'month',
                'target_amount' => $branch['target'],
                'effective_from' => '2026-06-01',
                'effective_to' => '2026-06-30',
                'description' => "Monthly sales target for {$branch['name']} branch - June 2026",
                'created_by' => 'Admin',
            ]);
        }

        // Daily Targets (Day-wise)
        foreach ($branches as $branch) {
            Target::create([
                'user_id' => $branch['employee'],
                'branch_id' => $branch['id'],
                'target_type' => 'day',
                'target_amount' => 50000, // 50k per day
                'effective_from' => '2026-06-01',
                'effective_to' => '2026-06-30',
                'description' => "Daily sales target for {$branch['name']} branch - ₹50,000/day",
                'created_by' => 'Admin',
            ]);
        }

        // ============================================================
        // COMPANY-WIDE TARGETS (No specific employee/branch)
        // ============================================================

        // Overall company monthly target
        Target::create([
            'user_id' => null,
            'branch_id' => null,
            'target_type' => 'month',
            'target_amount' => 5400000,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Company-wide monthly sales target - June 2026',
            'created_by' => 'Admin',
        ]);

        // Overall company daily target
        Target::create([
            'user_id' => null,
            'branch_id' => null,
            'target_type' => 'day',
            'target_amount' => 200000,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Company-wide daily sales target - ₹200,000/day',
            'created_by' => 'Admin',
        ]);

        echo "✅ Successfully seeded all targets!\n";
        echo "📊 Targets created:\n";
        echo "   • 4 Monthly employee targets (₹1.2M - ₹1.6M)\n";
        echo "   • 4 Daily employee targets (₹50K/day each)\n";
        echo "   • 1 Company-wide monthly target (₹5.4M)\n";
        echo "   • 1 Company-wide daily target (₹200K/day)\n";
        echo "\n   Total: 10 target records\n";
    }
}
