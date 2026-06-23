<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('DesignationMaster', function (Blueprint $table) {
            $table->increments('id');
            $table->string('DesignationCode', 50)->nullable();
            $table->string('Designation', 255);
            $table->string('CreatedBy', 100)->nullable();
            $table->timestamp('CreatedDate')->nullable();
            $table->string('ModifiedBy', 100)->nullable();
            $table->timestamp('ModifiedDate')->nullable();
            $table->tinyInteger('status')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DesignationMaster');
    }
};
