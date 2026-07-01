<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================================
        // SAMPLE TARGETS FOR JUNE 2026
        // ============================================================

        // ============================================================
        // SECTION 1: EMPLOYEE MONTHLY TARGETS
        // ============================================================

        // Employee 1: Monthly Target for Porur Branch
        Target::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 500000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'June 2026 Sales Target - Porur Location',
            'created_by' => 'Admin',
        ]);

        // Employee 2: Monthly Target for T-Nagar
        Target::create([
            'user_id' => 1002,
            'branch_id' => 2,
            'target_type' => 'month',
            'target_amount' => 450000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'June 2026 Sales Target - T-Nagar Location',
            'created_by' => 'Admin',
        ]);

        // Employee 3: Monthly Target for OMR
        Target::create([
            'user_id' => 1003,
            'branch_id' => 3,
            'target_type' => 'month',
            'target_amount' => 400000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'June 2026 Sales Target - OMR Location',
            'created_by' => 'Admin',
        ]);

        // Employee 4: Monthly Target for Madurai
        Target::create([
            'user_id' => 1004,
            'branch_id' => 4,
            'target_type' => 'month',
            'target_amount' => 350000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'June 2026 Sales Target - Madurai Location',
            'created_by' => 'Admin',
        ]);

        // ============================================================
        // SECTION 2: EMPLOYEE DAILY TARGETS
        // ============================================================

        // Daily Target for Employee 1 (Specific Day: 30-Jun-2026)
        Target::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'target_type' => 'day',
            'target_amount' => 25000.00,
            'effective_from' => '2026-06-30',
            'effective_to' => '2026-06-30',
            'description' => 'Daily sales target for Tuesday - Porur',
            'created_by' => 'Admin',
        ]);

        // Daily Target for Employee 2
        Target::create([
            'user_id' => 1002,
            'branch_id' => 2,
            'target_type' => 'day',
            'target_amount' => 22500.00,
            'effective_from' => '2026-06-30',
            'effective_to' => '2026-06-30',
            'description' => 'Daily sales target for Tuesday - T-Nagar',
            'created_by' => 'Admin',
        ]);

        // ============================================================
        // SECTION 3: BRANCH-WIDE TARGETS (No Specific Employee)
        // ============================================================

        // Porur Branch Total Target
        Target::create([
            'user_id' => null,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 2000000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Total branch target for all employees - Porur',
            'created_by' => 'Admin',
        ]);

        // T-Nagar Branch Total Target
        Target::create([
            'user_id' => null,
            'branch_id' => 2,
            'target_type' => 'month',
            'target_amount' => 1800000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Total branch target for all employees - T-Nagar',
            'created_by' => 'Admin',
        ]);

        // OMR Branch Total Target
        Target::create([
            'user_id' => null,
            'branch_id' => 3,
            'target_type' => 'month',
            'target_amount' => 1500000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Total branch target for all employees - OMR',
            'created_by' => 'Admin',
        ]);

        // ============================================================
        // SECTION 4: COMPANY-WIDE TARGETS (No Employee, No Branch)
        // ============================================================

        // Company Total Monthly Target
        Target::create([
            'user_id' => null,
            'branch_id' => null,
            'target_type' => 'month',
            'target_amount' => 5000000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Company-wide monthly revenue target for June',
            'created_by' => 'Admin',
        ]);

        // Company Daily Target
        Target::create([
            'user_id' => null,
            'branch_id' => null,
            'target_type' => 'day',
            'target_amount' => 200000.00,
            'effective_from' => '2026-06-30',
            'effective_to' => '2026-06-30',
            'description' => 'Company-wide daily revenue target',
            'created_by' => 'Admin',
        ]);

        // ============================================================
        // SECTION 5: ADDITIONAL MONTHLY TARGETS (Q2 Extended)
        // ============================================================

        // April Targets (Historical)
        Target::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 480000.00,
            'effective_from' => '2026-04-01',
            'effective_to' => '2026-04-30',
            'description' => 'April 2026 Sales Target - Porur',
            'created_by' => 'Admin',
        ]);

        // May Targets
        Target::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 490000.00,
            'effective_from' => '2026-05-01',
            'effective_to' => '2026-05-31',
            'description' => 'May 2026 Sales Target - Porur',
            'created_by' => 'Admin',
        ]);

        // July Targets (Future)
        Target::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 520000.00,
            'effective_from' => '2026-07-01',
            'effective_to' => '2026-07-31',
            'description' => 'July 2026 Sales Target - Porur',
            'created_by' => 'Admin',
        ]);

        echo "✅ Successfully seeded 15 sample targets!\n";
        echo "📊 Targets include:\n";
        echo "   • 4 Employee Monthly Targets\n";
        echo "   • 2 Employee Daily Targets\n";
        echo "   • 3 Branch-wide Targets\n";
        echo "   • 2 Company-wide Targets\n";
        echo "   • 3 Historical/Future Targets\n";
    }
}
