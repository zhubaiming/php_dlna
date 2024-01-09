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
        Schema::create('erp_goods', function (Blueprint $table) {
            $table->comment('erp商品表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->string('bar_code', 20)->nullable(false)->comment('条形码');
            $table->string('name', 50)->nullable(false)->comment('商品名');
            $table->string('specification', 20)->nullable(false)->comment('规格');
            $table->string('img', 255)->nullable(true)->comment('图片url地址');

            $table->unique(['bar_code', 'specification']);
            $table->index('bar_code');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_goods');
    }
};
