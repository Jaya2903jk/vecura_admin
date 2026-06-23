<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iou_settlements', function (Blueprint $table) {
            $table->increments('settlement_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->decimal('total_bill_amount', 15, 2)->default(0);
            $table->decimal('company_settlement_amount', 15, 2)->default(0);
            $table->decimal('employee_claim_amount', 15, 2)->default(0);
            $table->decimal('remaining_balance', 15, 2)->default(0);
            $table->string('settlement_type', 20)->nullable();
            $table->string('settlement_status', 50)->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->json('meta_data')->nullable();
            $table->decimal('claim_transfer_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iou_settlements');
    }
};
