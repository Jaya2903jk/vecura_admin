<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('BiomedicalTickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ticketId');
            $table->unsignedInteger('departmentId')->nullable();
            $table->unsignedInteger('categoryId')->nullable();
            $table->unsignedBigInteger('issueId')->nullable();
            $table->unsignedBigInteger('machineId')->nullable();
            $table->string('machineIssueType', 50)->nullable();
            $table->text('comments')->nullable();
            $table->string('status', 50)->default('pending');
            $table->json('meta_data')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->string('machineIssueIds')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('BiomedicalTickets');
    }
};
