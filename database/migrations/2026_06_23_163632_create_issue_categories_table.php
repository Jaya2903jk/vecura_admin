<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_categories', function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('category_name', 255);
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('department_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_categories');
    }
};
