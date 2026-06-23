<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('issue_id')->nullable();
            $table->unsignedBigInteger('facility_category_id')->nullable();
            $table->string('request_type', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->json('meta_data')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_ticket');
    }
};
