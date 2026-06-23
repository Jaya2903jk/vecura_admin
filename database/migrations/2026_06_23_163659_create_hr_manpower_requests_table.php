<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_manpower_requests_new', function (Blueprint $table) {
            $table->increments('manpowerRequestId');
            $table->unsignedBigInteger('ticketId');
            $table->unsignedInteger('departmentId')->nullable();
            $table->unsignedInteger('categoryId')->nullable();
            $table->unsignedInteger('escalationTypeId')->nullable();
            $table->string('designation', 150)->nullable();
            $table->tinyInteger('vacancies')->default(1);
            $table->text('jobDescription')->nullable();
            $table->tinyInteger('ageMin')->nullable();
            $table->tinyInteger('ageMax')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('experience', 100)->nullable();
            $table->string('qualification', 150)->nullable();
            $table->text('skills')->nullable();
            $table->string('workLocation', 150)->nullable();
            $table->string('requestType', 50)->nullable();
            $table->string('replacementFor', 150)->nullable();
            $table->string('approvalStatus', 50)->default('pending');
            $table->string('recruitmentStatus', 50)->default('open');
            $table->string('onboardingStatus', 50)->default('pending');
            $table->unsignedInteger('assigned_hr_id')->nullable();
            $table->text('remarks')->nullable();
            $table->string('attachmentPath')->nullable();
            $table->string('originalFileName')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->json('meta_data')->nullable();
            $table->unsignedInteger('employee_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_manpower_requests_new');
    }
};
