<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_relieving', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->date('resignation_date')->nullable();
            $table->date('notice_completion_date')->nullable();
            $table->date('relieving_date')->nullable();
            $table->text('reason_for_resignation')->nullable();
            $table->boolean('all_dues_cleared')->default(false);
            $table->boolean('equipment_returned')->default(false);
            $table->text('final_remarks')->nullable();
            $table->enum('relieving_status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_relieving');
    }
};
