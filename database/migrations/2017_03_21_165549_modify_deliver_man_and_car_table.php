<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDeliverManAndCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('delivery_mans', function (Blueprint $table) {
            $table->string('id_card_no')->comment('身份证号');
            $table->smallInteger('sex')->comment('性别');
            $table->string('phone')->comment('联系电话');
        });

        Schema::table('delivery_cars', function (Blueprint $table) {
            $table->string('car_no')->comment('车牌号');
            $table->smallInteger('carry_volume')->comment('运载体积');
            $table->string('phone')->comment('联系电话');
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
        Schema::table('delivery_mans', function (Blueprint $table) {
            $table->dropColumn('id_card_no');
            $table->dropColumn('sex');
            $table->dropColumn('phone');
        });

        Schema::table('delivery_cars', function (Blueprint $table) {
            $table->dropColumn('car_no');
            $table->dropColumn('carry_volume');
            $table->dropColumn('phone');
        });
    }
}
