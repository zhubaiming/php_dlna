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
        Schema::create('log_user_login_infos', function (Blueprint $table) {
            $table->comment('用户登录日志表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('用户唯一标识');
            $table->dateTime('created_at')->nullable(false)->comment('登录时间');
            $table->string('ip_address', 15)->nullable(false)->comment('登录ip');
            $table->unsignedTinyInteger('status')->nullable(false)->comment('登录状态: 0 - 失败,1 - 成功');
            $table->string('login_guard', 30)->nullable(false)->comment('登录方式来源');
            $table->string('device_model', 50)->nullable(false)->comment('使用设备型号');
            $table->string('device_system', 50)->nullable(false)->comment('使用设备系统');
            $table->string('wechat_SDK_version', 50)->nullable(true)->comment('微信SDK版本');
            $table->string('wechat_language', 50)->nullable(true)->comment('微信语言');
            $table->string('wechat_version', 50)->nullable(true)->comment('微信版本');

            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_user_login_infos');
    }
};
