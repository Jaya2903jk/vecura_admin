<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_requests', function (Blueprint $table) {
            $table->increments('claim_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->string('expense_type', 100)->nullable();
            $table->date('expense_date')->nullable();
            $table->decimal('expense_amount', 15, 2);
            $table->text('remarks')->nullable();
            $table->string('status', 50)->default('pending');
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_requests');
    }
};
