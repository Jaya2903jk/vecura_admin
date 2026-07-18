<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('document_type', [
                'Passport',
                'Aadhar',
                'PAN',
                'Driving License',
                'Voter ID',
                'Birth Certificate',
                'Medical Report',
                'Police Clearance',
                'Experience Letter',
                'Relieving Letter',
                'Other'
            ]);
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
