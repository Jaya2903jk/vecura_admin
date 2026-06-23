<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('country', function (Blueprint $table) {
            $table->increments('country_id');
            $table->string('country_name', 100);
            $table->string('country_code', 2)->unique();
            $table->boolean('is_active')->default(1);
        });
    }
    public function down(): void { Schema::dropIfExists('country'); }
};
