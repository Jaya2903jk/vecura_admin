<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalesDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n╔════════════════════════════════════════════════════════╗\n";
        echo "║  🚀 COMPREHENSIVE SALES DATA SEEDING (JUNE 2026)      ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";

        // 1. Seed Targets first (must come before achievements)
        echo "📌 Step 1: Seeding Targets...\n";
        $this->call(ComprehensiveTargetSeeder::class);

        // 2. Seed Daily Sales
        echo "\n📌 Step 2: Seeding Daily Sales...\n";
        $this->call(DailySalesSeeder::class);

        // 3. Seed In-House Sales
        echo "\n📌 Step 3: Seeding In-House Sales...\n";
        $this->call(InHouseSalesSeeder::class);

        // 4. Seed Nutritionist Sales
        echo "\n📌 Step 4: Seeding Nutritionist Sales...\n";
        $this->call(NutritionistSalesSeeder::class);

        // 5. Seed Consultant Sales
        echo "\n📌 Step 5: Seeding Consultant Sales...\n";
        $this->call(ConsultantSalesSeeder::class);

        // 6. Original SalesAchievement (if needed)
        echo "\n📌 Step 6: Seeding Sales Achievements (Legacy)...\n";
        $this->call(SalesAchievementSeeder::class);

        echo "\n╔════════════════════════════════════════════════════════╗\n";
        echo "║  ✅ ALL SALES DATA SEEDING COMPLETED!                 ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";

        echo "📊 SUMMARY:\n";
        echo "   ✓ 10   Target Records\n";
        echo "   ✓ 120  Daily Sales\n";
        echo "   ✓ 2    In-House Sales\n";
        echo "   ✓ 120  Nutritionist Sales\n";
        echo "   ✓ 76   Consultant Sales\n";
        echo "   ✓ 243  Sales Achievements (Legacy)\n";
        echo "   ─────────────────────────────────\n";
        echo "   ✓ 571+ TOTAL RECORDS\n\n";

        echo "🎯 REPORTS READY:\n";
        echo "   • http://10.10.1.143:8000/targets\n";
        echo "   • http://10.10.1.143:8000/sales-report\n";
        echo "   • http://10.10.1.143:8000/sales-report-complete\n\n";
    }
}
