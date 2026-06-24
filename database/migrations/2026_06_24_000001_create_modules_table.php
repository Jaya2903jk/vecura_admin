<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description')->nullable();
            $table->string('icon', 50)->default('ti-world-cog');
            $table->string('route_prefix', 50)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        // Seed default modules
        $modules = [
            ['name' => 'department', 'description' => 'Department Management', 'icon' => 'ti-building', 'route_prefix' => 'department'],
            ['name' => 'designation', 'description' => 'Designation Management', 'icon' => 'ti-briefcase', 'route_prefix' => 'designation'],
            ['name' => 'branch', 'description' => 'Branch Management', 'icon' => 'ti-building-skyscraper', 'route_prefix' => 'new-branch'],
            ['name' => 'location', 'description' => 'Location Management', 'icon' => 'ti-map-pin', 'route_prefix' => 'location'],
            ['name' => 'country', 'description' => 'Country Master', 'icon' => 'ti-world', 'route_prefix' => 'country'],
            ['name' => 'zone', 'description' => 'Zone Master', 'icon' => 'ti-zone', 'route_prefix' => 'zone'],
            ['name' => 'state', 'description' => 'State Master', 'icon' => 'ti-state', 'route_prefix' => 'state'],
            ['name' => 'city', 'description' => 'City Master', 'icon' => 'ti-city', 'route_prefix' => 'city'],
            ['name' => 'employee', 'description' => 'Employee Management', 'icon' => 'ti-users', 'route_prefix' => 'staff'],
            ['name' => 'ticket', 'description' => 'Ticket Management', 'icon' => 'ti-ticket', 'route_prefix' => 'tickets'],
            ['name' => 'iou', 'description' => 'IOU Request Management', 'icon' => 'ti-receipt', 'route_prefix' => 'iou'],
            ['name' => 'pcrequest', 'description' => 'PC Request Management', 'icon' => 'ti-currency', 'route_prefix' => 'pc'],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->updateOrInsert(
                ['name' => $module['name']],
                array_merge($module, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
