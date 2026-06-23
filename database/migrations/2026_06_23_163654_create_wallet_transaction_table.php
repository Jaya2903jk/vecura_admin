<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transaction', function (Blueprint $table) {
            $table->increments('txn_id');
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('direction', 10);
            $table->string('source_type', 50)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->text('narration')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transaction');
    }
};
