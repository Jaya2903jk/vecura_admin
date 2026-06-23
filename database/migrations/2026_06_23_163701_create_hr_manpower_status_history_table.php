<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_manpower_status_history', function (Blueprint $table) {
            $table->increments('historyId');
            $table->unsignedBigInteger('manpowerRequestId');
            $table->unsignedBigInteger('candidateId')->nullable();
            $table->string('oldStatus', 50)->nullable();
            $table->string('newStatus', 50);
            $table->text('remarks')->nullable();
            $table->unsignedInteger('changedBy')->nullable();
            $table->timestamp('changedAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_manpower_status_history');
    }
};
