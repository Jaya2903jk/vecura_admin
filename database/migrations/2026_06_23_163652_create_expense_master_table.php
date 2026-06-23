<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_master', function (Blueprint $table) {
            $table->increments('ExpenseId');
            $table->string('ExpenseName', 150);
            $table->text('Description')->nullable();
            $table->tinyInteger('Status')->default(1);
            $table->string('CreatedBy', 100)->nullable();
            $table->timestamp('CreatedDate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_master');
    }
};
