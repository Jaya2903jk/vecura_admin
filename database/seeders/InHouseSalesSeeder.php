<?php

namespace Database\Seeders;

use App\Models\InHouseSales;
use Illuminate\Database\Seeder;

class InHouseSalesSeeder extends Seeder
{
    public function run(): void
    {
        // PORUR - In-House Sales
        InHouseSales::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'sale_date' => '2026-06-30',
            'day_target' => 300000,
            'day_sales' => 274075,
            'visits' => 5,
            'joined' => 2,
            'packages_sold' => 3,
            'entered_by' => 'Admin',
        ]);

        // T-NAGAR - In-House Sales
        InHouseSales::create([
            'user_id' => 1002,
            'branch_id' => 2,
            'sale_date' => '2026-06-30',
            'day_target' => 400000,
            'day_sales' => 469649,
            'visits' => 8,
            'joined' => 3,
            'packages_sold' => 4,
            'entered_by' => 'Admin',
        ]);

        echo "✅ 2 In-House Sales records created\n";
    }
}
