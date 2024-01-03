<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_user_levels', function (Blueprint $table) {
            $table->comment('用户等级表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('用户唯一标识');
            $table->unsignedSmallInteger('level')->nullable(false)->comment('用户等级');
            $table->unsignedInteger('point')->nullable(false)->comment('用户积分');
            $table->unsignedInteger('point_total')->nullable(false)->comment('用户历史积分');

            $table->index('user_id');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_user_levels');
    }
};
