<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_bonds', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->integer('bond_duration_years');
            $table->date('bond_start_date');
            $table->date('bond_end_date')->nullable();
            $table->decimal('bond_amount', 12, 2)->nullable();
            $table->text('bond_conditions')->nullable();
            $table->string('bond_document_file')->nullable();
            $table->enum('bond_status', ['Active', 'Completed', 'Cancelled'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_bonds');
    }
};
