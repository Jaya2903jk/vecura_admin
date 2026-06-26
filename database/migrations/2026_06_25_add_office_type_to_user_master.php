<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('User_Master', function (Blueprint $table) {
            if (!Schema::hasColumn('User_Master', 'office_type')) {
                $table->enum('office_type', ['Branch Location', 'Corporate Office', 'Head Office', 'Regional Office'])
                    ->default('Branch Location')
                    ->after('branch_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('User_Master', function (Blueprint $table) {
            $table->dropColumn(['office_type']);
        });
    }
};
