<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderrTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no')->comment('配送单号');
            $table->smallInteger('status')->comment('配送单状态');
            $table->integer('order_id')->comment('订单编号');
            $table->integer('delivery_user')->comment('配送人');
            $table->integer('delivery_car')->comment('配送车辆');
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
        Schema::drop('delivery_orders');
    }
}
