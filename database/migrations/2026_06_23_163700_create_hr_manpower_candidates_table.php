<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_manpower_candidates', function (Blueprint $table) {
            $table->increments('candidateId');
            $table->unsignedBigInteger('manpowerRequestId');
            $table->string('candidateName', 150);
            $table->string('mobile', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->date('interviewDate')->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('employeeId')->nullable();
            $table->unsignedInteger('createdBy')->nullable();
            $table->unsignedInteger('updatedBy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_manpower_candidates');
    }
};
