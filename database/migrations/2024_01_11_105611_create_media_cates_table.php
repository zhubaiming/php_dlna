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
        Schema::create('media_cates', function (Blueprint $table) {
            $table->comment('媒体资源分类表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedInteger('pid')->nullable(false)->default(0)->comment('父id');
            $table->string('name', 30)->nullable(false)->comment('名称');
            $table->string('name_en', 30)->nullable(true)->comment('名称(英文)');
            $table->unsignedSmallInteger('sort')->nullable(true)->comment('排序');
            $table->string('type', 30)->nullable(false)->comment('类型名称');
            $table->string('belonging_to', 30)->nullable(false)->comment('所属: 0 - 公共,1 - 视频,2 - 音乐,3 - 照片');

            $table->unique(['pid', 'type', 'sort']);
            $table->index('name');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_cates');
    }
};
