<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('state', function (Blueprint $table) {
            $table->increments('state_id');
            $table->unsignedInteger('country_id');
            $table->string('state_code', 10);
            $table->string('state_name', 100);
            $table->boolean('is_active')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('state');
    }
};
