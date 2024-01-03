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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->comment('用户信息表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedBigInteger('user_id')->nullable(false)->unique()->comment('用户唯一标识');
            $table->string('pure_name', 30)->nullable(false)->comment('用户真实姓名');
            $table->string('idcard', 20)->nullable(false)->unique()->comment('用户身份证号码');
            $table->string('nick_name', 20)->nullable(false)->comment('用户昵称');
            $table->unsignedTinyInteger('gender')->nullable(false)->comment('用户性别: 0 - 未知,1 - 男,2 - 女');
            $table->string('country', 20)->nullable(true)->comment('用户所在国家');
            $table->string('province', 20)->nullable(true)->comment('用户所在省份');
            $table->string('city', 20)->nullable(true)->comment('用户所在城市');
            $table->date('birthday')->nullable(false)->comment('用户生日');

            $table->index('pure_name');
            $table->index('gender');
            $table->index('birthday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
