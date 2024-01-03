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
        Schema::create('log_user_point_infos', function (Blueprint $table) {
            $table->comment('用户积分变动日志表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('用户唯一标识');
            $table->unsignedInteger('point')->nullable(false)->comment('用户积分记录');
            $table->string('point_reasons', 255)->nullable(false)->comment('积分变动原因');
            $table->dateTime('created_at')->nullable(false)->comment('变动时间');

            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_user_point_infos');
    }
};
