<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_bill_item', function (Blueprint $table) {
            $table->increments('item_id');
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->text('bill_description')->nullable();
            $table->string('bill_number', 100)->nullable();
            $table->date('bill_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('attachment_path')->nullable();
            $table->string('item_status', 50)->default('pending');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_bill_item');
    }
};
