<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectReasonToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('orders', function (Blueprint $table) {
           $table->addColumn('text', 'reject_reason')->comment('拒绝理由');
           $table->addColumn('text', 'action_seller')->comment('销售管理ID');
           $table->integer('deliver_status')->comment('签收状态');
           $table->timestamp('received_time')->comment('签收时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
            $table->dropColumn('action_seller');
            $table->dropColumn('deliver_status');
            $table->dropColumn('received_time');
        });
    }
}
