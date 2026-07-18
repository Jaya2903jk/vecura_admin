<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designation_department', function (Blueprint $table) {
            $table->id();
            $table->integer('designation_id')->nullable();
            $table->string('designation_code', 50);
            $table->integer('department_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['designation_code', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designation_department');
    }
};
