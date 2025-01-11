<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_count')->default(1);
            $table->string('phone', 15)->nullable(); 
            $table->json('items')->nullable(); // Stores all cart items in JSON format
            $table->decimal('subtotal', 8, 2)->default(0.00);
            $table->decimal('sst', 8, 2)->default(0.00);
            $table->decimal('rounding', 8, 2)->default(0.00);
            $table->decimal('total', 8, 2)->default(0.00);
            $table->string('status')->default('pending'); // 订单状态（默认是预订单）
            $table->json('table_ids')->nullable(); // 修改为支持多个桌位ID（json格式）
            $table->unsignedBigInteger('user_info_id')->nullable(); // 添加外键字段，用于与 UserInfo 关联
            $table->timestamps();

            $table->foreign('user_info_id')->references('id')->on('user_infos')->onDelete('set null'); // 设置与 UserInfo 的外键约束

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_orders');
    }
};
