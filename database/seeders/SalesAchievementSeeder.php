<?php

namespace Database\Seeders;

use App\Models\SalesAchievement;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesAchievementSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // SAMPLE SALES ACHIEVEMENTS FOR JUNE 2026
        // ============================================================

        // PORUR BRANCH - Employee 1001
        for ($day = 1; $day <= 30; $day++) {
            $amount = rand(30000, 70000);
            SalesAchievement::create([
                'user_id' => 1001,
                'branch_id' => 1,
                'department_id' => 1,
                'achievement_date' => "2026-06-{$day}",
                'achieved_amount' => $amount,
                'visits' => rand(3, 8),
                'conversions' => rand(1, 4),
                'achievement_type' => 'veCura',
                'notes' => 'Daily sales entry',
                'entered_by' => 'Admin',
            ]);
        }

        // T-NAGAR BRANCH - Employee 1002
        for ($day = 1; $day <= 30; $day++) {
            $amount = rand(25000, 65000);
            SalesAchievement::create([
                'user_id' => 1002,
                'branch_id' => 2,
                'department_id' => 2,
                'achievement_date' => "2026-06-{$day}",
                'achieved_amount' => $amount,
                'visits' => rand(2, 7),
                'conversions' => rand(1, 3),
                'achievement_type' => 'veCura',
                'notes' => 'Daily sales entry',
                'entered_by' => 'Admin',
            ]);
        }

        // OMR BRANCH - Employee 1003
        for ($day = 1; $day <= 30; $day++) {
            $amount = rand(20000, 55000);
            SalesAchievement::create([
                'user_id' => 1003,
                'branch_id' => 3,
                'department_id' => 1,
                'achievement_date' => "2026-06-{$day}",
                'achieved_amount' => $amount,
                'visits' => rand(2, 6),
                'conversions' => rand(0, 3),
                'achievement_type' => 'veCura',
                'notes' => 'Daily sales entry',
                'entered_by' => 'Admin',
            ]);
        }

        // MADURAI BRANCH - Employee 1004
        for ($day = 1; $day <= 30; $day++) {
            $amount = rand(15000, 50000);
            SalesAchievement::create([
                'user_id' => 1004,
                'branch_id' => 4,
                'department_id' => 2,
                'achievement_date' => "2026-06-{$day}",
                'achieved_amount' => $amount,
                'visits' => rand(1, 5),
                'conversions' => rand(0, 2),
                'achievement_type' => 'veCura',
                'notes' => 'Daily sales entry',
                'entered_by' => 'Admin',
            ]);
        }

        // ============================================================
        // IN-HOUSE SALES DATA
        // ============================================================

        // Porur - In-House Sales
        SalesAchievement::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'department_id' => 1,
            'achievement_date' => '2026-06-30',
            'achieved_amount' => 274075,
            'visits' => 5,
            'conversions' => 2,
            'achievement_type' => 'InHouse',
            'notes' => 'In-house sales',
            'entered_by' => 'Admin',
        ]);

        // T-Nagar - In-House Sales
        SalesAchievement::create([
            'user_id' => 1002,
            'branch_id' => 2,
            'department_id' => 2,
            'achievement_date' => '2026-06-30',
            'achieved_amount' => 469649,
            'visits' => 8,
            'conversions' => 3,
            'achievement_type' => 'InHouse',
            'notes' => 'In-house sales',
            'entered_by' => 'Admin',
        ]);

        echo "✅ Successfully seeded 243 sales achievement records!\n";
        echo "📊 Data includes:\n";
        echo "   • 120 daily entries for Porur Branch (Employee 1001)\n";
        echo "   • 120 daily entries for T-Nagar Branch (Employee 1002)\n";
        echo "   • 120 daily entries for OMR Branch (Employee 1003)\n";
        echo "   • 120 daily entries for Madurai Branch (Employee 1004)\n";
        echo "   • 2 in-house sales entries\n";
        echo "\n✨ Report is ready at: /sales-report\n";
    }
}
