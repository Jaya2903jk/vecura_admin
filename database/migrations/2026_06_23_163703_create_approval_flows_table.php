<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ApprovalFlows', function (Blueprint $table) {
            $table->increments('flowId');
            $table->unsignedBigInteger('issueId');
            $table->tinyInteger('levelOrder');
            $table->unsignedInteger('roleId');
            $table->string('levelName', 100)->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ApprovalFlows');
    }
};
