<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_balances', function (Blueprint $table) {
            $table->increments('balance_id');
            $table->unsignedInteger('employee_id')->unique();
            $table->decimal('total_iou_amount', 15, 2)->default(0);
            $table->decimal('total_settlement_amount', 15, 2)->default(0);
            $table->decimal('total_claim_amount', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('pending_claim_amount', 15, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_balances');
    }
};
