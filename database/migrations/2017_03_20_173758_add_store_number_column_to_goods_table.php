<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreNumberColumnToGoodsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('goods', function (Blueprint $table) {
            $table->integer('store_num');
            $table->string('bar_code');
            $table->string('supply_company');
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
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('store_num');
            $table->dropColumn('bar_code');
            $table->dropColumn('supply_company');
        });
    }
}
