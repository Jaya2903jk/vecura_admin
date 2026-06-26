<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('User_Master', function (Blueprint $table) {
            if (!Schema::hasColumn('User_Master', 'manager_id')) {
                $table->integer('manager_id')->nullable()->after('office_type')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('User_Master', function (Blueprint $table) {
            if (Schema::hasColumn('User_Master', 'manager_id')) {
                $table->dropIndex(['manager_id']);
                $table->dropColumn('manager_id');
            }
        });
    }
};
