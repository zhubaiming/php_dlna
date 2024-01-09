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
        Schema::create('erp_goods_market_prices', function (Blueprint $table) {
            $table->comment('erp商品市场售价表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedInteger('goods_id')->nullable(false)->comment('商品id');
            $table->string('bar_code', 20)->nullable(false)->comment('条形码');
            $table->string('name', 50)->nullable(false)->comment('商品名');
            $table->string('specification', 50)->nullable(false)->comment('规格');
            $table->unsignedInteger('price')->nullable(false)->default(0)->comment('该商品销售单价');

            $table->unique(['bar_code', 'specification']);
            $table->index('goods_id');
            $table->index('bar_code');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_goods_market_prices');
    }
};
