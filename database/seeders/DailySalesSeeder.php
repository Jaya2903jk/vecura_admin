<?php

namespace Database\Seeders;

use App\Models\DailySales;
use Illuminate\Database\Seeder;

class DailySalesSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['id' => 1, 'name' => 'PORUR', 'employees' => [1001]],
            ['id' => 2, 'name' => 'T NAGAR', 'employees' => [1002]],
            ['id' => 3, 'name' => 'OMR', 'employees' => [1003]],
            ['id' => 4, 'name' => 'MADURAI', 'employees' => [1004]],
        ];

        foreach ($branches as $branch) {
            foreach ($branch['employees'] as $emp_id) {
                for ($day = 1; $day <= 30; $day++) {
                    $baseAmount = match($branch['id']) {
                        1 => rand(35000, 75000),
                        2 => rand(30000, 70000),
                        3 => rand(25000, 60000),
                        4 => rand(20000, 55000),
                    };

                    DailySales::create([
                        'user_id' => $emp_id,
                        'branch_id' => $branch['id'],
                        'sale_date' => "2026-06-{$day}",
                        'day_target' => 50000,
                        'day_sales' => $baseAmount,
                        'visits' => rand(3, 8),
                        'joined' => rand(0, 3),
                        'cc_appointments' => rand(1, 5),
                        'sales_type' => 'new',
                        'entered_by' => 'Admin',
                    ]);
                }
            }
        }

        echo "✅ 120 Daily Sales records created for 4 branches (June 2026)\n";
    }
}
