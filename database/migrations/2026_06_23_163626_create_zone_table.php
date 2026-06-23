<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zone', function (Blueprint $table) {
            $table->increments('zone_id');
            $table->unsignedInteger('country_id');
            $table->string('zone_code', 20);
            $table->string('zone_name', 100);
            $table->string('region_type', 50)->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone');
    }
};
