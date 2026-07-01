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
        Schema::create('in_house_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->date('sale_date')->index();
            $table->decimal('day_target', 15, 2)->default(0);
            $table->decimal('day_sales', 15, 2)->default(0);
            $table->unsignedInteger('visits')->default(0);
            $table->unsignedInteger('joined')->default(0);
            $table->unsignedInteger('packages_sold')->default(0);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('in_house_sales');
    }
};
