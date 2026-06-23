<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MachineIssuesTable', function (Blueprint $table) {
            $table->increments('machineIssueId');
            $table->string('IssuesName', 200);
            $table->unsignedBigInteger('MachineId');
            $table->string('Type', 50)->nullable();
            $table->tinyInteger('Status')->default(1);
            $table->string('CreatedBy', 100)->nullable();
            $table->timestamp('CreatedDate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MachineIssuesTable');
    }
};
