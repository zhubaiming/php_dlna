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
        Schema::create('erp_purchase_goods_records', function (Blueprint $table) {
            $table->comment('erp进货记录表');

            $table->id()->nullable(false)->from(1)->comment('');
            $table->unsignedInteger('goods_id')->nullable(false)->comment('商品id');
            $table->string('bar_code', 20)->nullable(false)->comment('条形码');
            $table->string('name', 50)->nullable(false)->comment('商品名');
            $table->string('specification', 50)->nullable(false)->comment('规格');
            $table->unsignedInteger('number')->nullable(false)->default(0)->comment('商品数量');
            $table->unsignedInteger('price')->nullable(false)->default(0)->comment('该商品进货单价');
            $table->unsignedInteger('total_price')->nullable(false)->default(0)->comment('该商品进货总价');
            $table->string('factory', 50)->nullable(false)->comment('进货厂家');
            $table->date('purchased_at')->nullable(false)->comment('进货日期');
            $table->dateTime('created_at')->nullable(false)->comment('录入时间');

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
        Schema::dropIfExists('erp_purchase_goods_records');
    }
};
