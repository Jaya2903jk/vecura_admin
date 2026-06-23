<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_wallet', function (Blueprint $table) {
            $table->increments('wallet_id');
            $table->unsignedInteger('branch_id')->unique();
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->decimal('total_credited', 15, 2)->default(0);
            $table->decimal('total_debited', 15, 2)->default(0);
            $table->timestamp('last_updated')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_wallet');
    }
};
