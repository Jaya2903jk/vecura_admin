<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_followups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('complaint_id')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->string('level', 20)->nullable();
            $table->string('assigned_to')->nullable();
            $table->unsignedInteger('action_by')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_followups');
    }
};
