<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_educational_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->enum('document_type', [
                'Degree',
                '10th Certificate',
                '12th Certificate',
                'Diploma',
                'Certificate Course',
                'Other'
            ]);
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('file_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_educational_documents');
    }
};
