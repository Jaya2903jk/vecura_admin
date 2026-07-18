<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->enum('employee_category', ['White Collar', 'Blue Collar'])->default('White Collar');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('alternate_phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->enum('employee_type', ['Permanent', 'Temporary', 'Contract'])->default('Permanent');
            $table->date('date_of_joining')->nullable();
            $table->date('date_of_resignation')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('aadhar_number', 20)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->string('bank_account_number', 20)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
