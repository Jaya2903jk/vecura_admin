<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch', function (Blueprint $table) {
            $table->increments('branch_id');
            $table->unsignedInteger('zone_id');
            $table->unsignedInteger('city_id');
            $table->string('branch_name', 150);
            $table->string('branch_code', 20)->unique();
            $table->string('manager_name', 100)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->boolean('is_active')->default(1);
        });
        // Schema::create('branche', function (Blueprint $table) {
        //     $table->id('branch_id');
        //     $table->string('branch_name', 100)->unique();
        //     $table->string('branch_code', 10)->unique(); // BR001, BR002
        //     $table->string('city_id', 50);
        //     $table->string('region', 50)->nullable();
        //     $table->unsignedBigInteger('manager_id')->nullable();
        //     $table->string('manager_name', 100)->nullable();
        //     $table->boolean('is_active')->default(true);
        //     $table->timestamps();
        //     $table->index('region');
        //     $table->index('is_active');
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch');
    }
};
