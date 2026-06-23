<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('city', function (Blueprint $table) {
            $table->increments('city_id');
            $table->unsignedInteger('state_id');
            $table->string('city_name', 100);
            $table->string('pincode', 20)->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city');
    }
};
