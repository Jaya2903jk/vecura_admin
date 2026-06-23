<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iou_settlement_items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->unsignedBigInteger('settlement_id');
            $table->string('expense_type', 100)->nullable();
            $table->date('bill_date')->nullable();
            $table->decimal('bill_amount', 15, 2)->default(0);
            $table->decimal('settlement_amount', 15, 2)->default(0);
            $table->decimal('employee_claim_amount', 15, 2)->default(0);
            $table->string('bill_file')->nullable();
            $table->text('remarks')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iou_settlement_items');
    }
};
