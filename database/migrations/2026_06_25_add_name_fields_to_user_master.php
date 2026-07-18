<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('User_Master', function (Blueprint $table) {
            // Add only if not exists
            if (!Schema::hasColumn('User_Master', 'first_name')) {
                $table->string('first_name')->nullable()->after('UserID');
            }
            if (!Schema::hasColumn('User_Master', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('User_Master', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('User_Master', 'employee_code')) {
                $table->string('employee_code', 50)->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('User_Master', 'department_id')) {
                $table->integer('department_id')->nullable()->after('employee_code');
            }
            if (!Schema::hasColumn('User_Master', 'designation_code')) {
                $table->string('designation_code', 50)->nullable()->after('department_id');
            }
            if (!Schema::hasColumn('User_Master', 'employee_status')) {
                $table->enum('employee_status', ['Active', 'Inactive', 'On Leave', 'Relieved'])->default('Active')->after('branch_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('User_Master', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'date_of_birth', 'employee_code', 'department_id', 'designation_code', 'employee_status']);
        });
    }
};
