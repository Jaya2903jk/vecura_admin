<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location', function (Blueprint $table) {
            $table->increments('location_id');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('branch_id')->nullable();
            $table->string('location_name', 150);
            $table->text('address')->nullable();
            $table->string('pincode', 20)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location');
    }
};
