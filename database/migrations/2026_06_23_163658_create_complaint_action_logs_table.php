<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ComplaintActionLogs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ComplaintId')->nullable();
            $table->string('Action', 100)->nullable();
            $table->text('Comment')->nullable();
            $table->unsignedInteger('UserId')->nullable();
            $table->string('UserName', 150)->nullable();
            $table->unsignedInteger('RoleId')->nullable();
            $table->string('Level', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ComplaintActionLogs');
    }
};
