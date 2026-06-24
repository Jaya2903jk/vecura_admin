<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('modules', 'parent')) {
                $table->string('parent')->nullable()->comment('Parent menu category (e.g., Masters, RBAC)');
            }
            if (!Schema::hasColumn('modules', 'sort_order')) {
                $table->integer('sort_order')->default(0)->comment('Display order in menu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'parent')) {
                $table->dropColumn('parent');
            }
            if (Schema::hasColumn('modules', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
