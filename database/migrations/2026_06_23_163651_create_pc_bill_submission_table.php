<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_bill_submission', function (Blueprint $table) {
            $table->increments('submission_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->unsignedInteger('raised_by')->nullable();
            $table->string('description')->nullable();
            $table->decimal('total_bill_amount', 15, 2)->default(0);
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->string('accounts_status', 50)->default('pending');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('accounts_remarks')->nullable();
            $table->string('ticket_status', 50)->default('open');
            $table->decimal('wallet_balance_at_raise', 15, 2)->default(0);
            $table->timestamp('closed_at')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_bill_submission');
    }
};
