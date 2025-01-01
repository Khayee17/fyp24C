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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->integer('numberOfCustomers');
            $table->unsignedBigInteger('order_id')->nullable();  // 添加 order_id 字段，nullable 允许为空
            $table->foreign('order_id')->references('id')->on('my_orders')->onDelete('cascade'); // 添加外键约束
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropForeign(['order_id']); // 删除外键约束
            $table->dropColumn('order_id');  // 删除 order_id 字段
        });

        Schema::dropIfExists('user_infos'); // 删除整个表
    }
};
