<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_medical', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('blood_group', 5)->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->text('medical_history')->nullable();
            $table->date('last_medical_checkup_date')->nullable();
            $table->date('next_medical_checkup_date')->nullable();
            $table->string('medical_report_file')->nullable();
            $table->boolean('medical_fitness_certified')->default(false);
            $table->date('medical_fitness_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_medical');
    }
};
