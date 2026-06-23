<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MachineTable', function (Blueprint $table) {
            $table->increments('MachineId');
            $table->string('MachineName', 200);
            $table->string('MachineRelated', 200)->nullable();
            $table->tinyInteger('Status')->default(1);
            $table->string('CreatedBy', 100)->nullable();
            $table->timestamp('CreatedDate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MachineTable');
    }
};
