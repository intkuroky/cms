<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order_goods', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('goods_id');
            $table->integer('goods_name');
            $table->integer('order_id');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('comment');
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
        Schema::drop('order_goods');
    }
}
