<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedInteger('department_id')->nullable()->index();
            $table->date('achievement_date')->index();
            $table->decimal('achieved_amount', 15, 2)->default(0);
            $table->decimal('visits', 5, 0)->default(0);
            $table->decimal('conversions', 5, 0)->default(0);
            $table->string('achievement_type')->default('veCura'); // veCura, InHouse, etc.
            $table->string('notes')->nullable();
            $table->string('entered_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_achievements');
    }
};
