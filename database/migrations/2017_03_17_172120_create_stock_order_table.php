<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('stock_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no')->comment('备货单号');
            $table->smallInteger('status')->comment('备货单状态');
            $table->integer('order_id')->comment('订单编号');
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
        //
        Schema::drop('stock_orders');
    }
}
