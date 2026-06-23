<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iou_requests', function (Blueprint $table) {
            $table->increments('iou_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedInteger('employee_id')->nullable();
            $table->decimal('requested_amount', 15, 2)->default(0);
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('settlement_amount', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->string('Department', 100)->nullable();
            $table->string('Category', 100)->nullable();
            $table->string('TypeOfEscalation', 100)->nullable();
            $table->string('branch_id')->nullable();
            $table->timestamp('request_date')->nullable();
            $table->text('purpose')->nullable();
            $table->string('status', 50)->default('pending');
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('paid_by')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('settlement_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iou_requests');
    }
};
