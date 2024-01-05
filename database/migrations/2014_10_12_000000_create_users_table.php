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
        Schema::create('users', function (Blueprint $table) {
            $table->comment('用户表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedBigInteger('user_id')->nullable(false)->unique()->comment('用户唯一标识');
            $table->string('account', 30)->nullable(false)->comment('用户登录账号');
            $table->string('password', 100)->nullable(false)->comment('用户登录密码');
            $table->string('country_code', 10)->nullable(false)->comment('手机号国家区域号');
            $table->string('pure_phone_number', 11)->nullable(false)->comment('没有区号的手机号');
            $table->string('invitation_code', 50)->nullable(true)->comment('注册邀请码(暂时无用)');
            $table->string('origin', 30)->nullable(false)->comment('用户注册来源');
            $table->string('app_id', 50)->nullable(false)->comment('注册应用id');
            $table->string('wechat_openid', 50)->nullable(true)->comment('微信openid');
            $table->string('wechat_unionid', 50)->nullable(true)->comment('微信unionid');
            $table->dateTime('sing_in_at')->nullable(false)->comment('注册时间');
            $table->tinyInteger('status')->nullable(false)->comment('用户状态: 0 - 正常,1 - 冻结,-1 - 删除/注销');

            $table->unique(['country_code', 'pure_phone_number', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
