<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickerUserColumnToPickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pick_orders', function (Blueprint $table) {
           $table->integer('pick_user')->comment('分拣人');
           $table->integer('submit_user')->comment('提交人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pick_orders', function (Blueprint $table) {
            $table->dropColumn('pick_user');
            $table->dropColumn('submit_user');
        });
    }
}
