<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issueMasterTest', function (Blueprint $table) {
            $table->increments('IssueId');
            $table->unsignedInteger('DepartmentId');
            $table->unsignedInteger('CategoryId');
            $table->string('IssueName', 255);
            $table->tinyInteger('Status')->default(1);
            $table->string('Level1Role')->nullable();
            $table->string('Level2Role')->nullable();
            $table->string('Level3Role')->nullable();
            $table->string('Level4Role')->nullable();
            $table->string('Level5Role')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issueMasterTest');
    }
};
