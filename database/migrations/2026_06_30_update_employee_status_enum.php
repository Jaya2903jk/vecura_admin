<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQL Server, we need to drop and recreate the constraint
        if (DB::connection()->getDriverName() === 'sqlsrv') {
            // Drop the check constraint
            DB::statement('ALTER TABLE [User_Master] DROP CONSTRAINT CK__User_Mast__emplo__2864999A');

            // Add new constraint with all allowed values
            DB::statement("ALTER TABLE [User_Master] ADD CONSTRAINT CK_UserMaster_employee_status CHECK (
                [employee_status] IN ('Active', 'Inactive', 'On Probation', 'Confirmed', 'Notice Period', 'Resigned', 'Terminated', 'Absconding', 'On Leave', 'Relieved')
            )");
        } else {
            // For other databases, modify the enum
            Schema::table('User_Master', function (Blueprint $table) {
                $table->enum('employee_status', [
                    'Active',
                    'Inactive',
                    'On Probation',
                    'Confirmed',
                    'Notice Period',
                    'Resigned',
                    'Terminated',
                    'Absconding',
                    'On Leave',
                    'Relieved'
                ])->default('Active')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlsrv') {
            // Revert to original constraint
            DB::statement('ALTER TABLE [User_Master] DROP CONSTRAINT CK_UserMaster_employee_status');

            DB::statement("ALTER TABLE [User_Master] ADD CONSTRAINT CK__User_Mast__emplo__2864999A CHECK (
                [employee_status] IN ('Active', 'Inactive', 'On Leave', 'Relieved')
            )");
        } else {
            Schema::table('User_Master', function (Blueprint $table) {
                $table->enum('employee_status', [
                    'Active',
                    'Inactive',
                    'On Leave',
                    'Relieved'
                ])->default('Active')->change();
            });
        }
    }
};
