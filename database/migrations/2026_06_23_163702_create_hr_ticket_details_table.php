<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_ticket_details', function (Blueprint $table) {
            $table->increments('hrTicketId');
            $table->unsignedBigInteger('ticketId');
            $table->unsignedInteger('departmentId')->nullable();
            $table->unsignedInteger('categoryId')->nullable();
            $table->unsignedInteger('escalationTypeId')->nullable();
            $table->unsignedInteger('employeeId')->nullable();
            $table->string('employeeName', 200)->nullable();
            $table->date('fromDate')->nullable();
            $table->date('toDate')->nullable();
            $table->date('attendanceDate')->nullable();
            $table->text('comments')->nullable();
            $table->string('attachmentPath')->nullable();
            $table->string('originalFileName')->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamp('createdDate')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->json('status_history')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_ticket_details');
    }
};
