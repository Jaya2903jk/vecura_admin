<?php

namespace Database\Seeders;

use App\Models\NutritionistSales;
use Illuminate\Database\Seeder;

class NutritionistSalesSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['id' => 1, 'name' => 'PORUR'],
            ['id' => 2, 'name' => 'T NAGAR'],
            ['id' => 3, 'name' => 'OMR'],
            ['id' => 4, 'name' => 'MADURAI'],
        ];

        foreach ($branches as $branch) {
            for ($day = 1; $day <= 30; $day++) {
                $baseAmount = match($branch['id']) {
                    1 => rand(15000, 35000),
                    2 => rand(12000, 30000),
                    3 => rand(10000, 25000),
                    4 => rand(8000, 20000),
                };

                NutritionistSales::create([
                    'user_id' => 1000 + $branch['id'],
                    'branch_id' => $branch['id'],
                    'sale_date' => "2026-06-{$day}",
                    'sales_amount' => $baseAmount,
                    'consultations' => rand(1, 4),
                    'entered_by' => 'Admin',
                ]);
            }
        }

        echo "✅ 120 Nutritionist Sales records created (June 2026)\n";
    }
}
