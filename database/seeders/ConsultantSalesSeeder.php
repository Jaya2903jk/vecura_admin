<?php

namespace Database\Seeders;

use App\Models\ConsultantSales;
use Illuminate\Database\Seeder;

class ConsultantSalesSeeder extends Seeder
{
    public function run(): void
    {
        $consultants = [
            ['id' => 1, 'name' => 'Dr. Ramesh Kumar', 'branch' => 1],
            ['id' => 2, 'name' => 'Dr. Priya Singh', 'branch' => 2],
            ['id' => 3, 'name' => 'Dr. Arjun Patel', 'branch' => 3],
            ['id' => 4, 'name' => 'Dr. Anita Sharma', 'branch' => 4],
        ];

        foreach ($consultants as $consultant) {
            for ($day = 1; $day <= 30; $day++) {
                if (rand(1, 3) > 1) { // 66% days have sales
                    ConsultantSales::create([
                        'user_id' => 1000 + $consultant['branch'],
                        'branch_id' => $consultant['branch'],
                        'sale_date' => "2026-06-{$day}",
                        'sales_amount' => rand(20000, 60000),
                        'consultations' => rand(1, 3),
                        'consultant_name' => $consultant['name'],
                        'entered_by' => 'Admin',
                    ]);
                }
            }
        }

        echo "✅ Consultant Sales records created (June 2026)\n";
    }
}
