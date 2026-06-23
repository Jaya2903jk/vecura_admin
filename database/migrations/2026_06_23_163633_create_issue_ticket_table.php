<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issueTicket', function (Blueprint $table) {
            $table->increments('ticketId');
            $table->string('Subject', 500)->nullable();
            $table->string('Branch', 100)->nullable();
            $table->string('Department', 100)->nullable();
            $table->text('Brief')->nullable();
            $table->string('Status', 50)->nullable();
            $table->string('Priority', 20)->nullable();
            $table->string('Issuelevel1')->nullable();
            $table->string('Issuelevel2')->nullable();
            $table->string('Issuelevel3')->nullable();
            $table->string('Issuelevel4')->nullable();
            $table->string('Issuelevel5')->nullable();
            $table->string('AcceptedBy')->nullable();
            $table->string('RequiredTime')->nullable();
            $table->string('RequiredTimeType')->nullable();
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedDate')->nullable();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedDate')->nullable();
            $table->string('AttachFile')->nullable();
            $table->string('CustomerCode')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issueTicket');
    }
};
