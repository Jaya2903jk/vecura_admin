<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_manpower_assignment', function (Blueprint $table) {
            $table->increments('assignmentId');
            $table->unsignedBigInteger('manpowerRequestId');
            $table->unsignedInteger('assignedTo')->nullable();
            $table->unsignedInteger('assignedBy')->nullable();
            $table->timestamp('assignedDate')->nullable();
            $table->boolean('isSelfAssigned')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_manpower_assignment');
    }
};
