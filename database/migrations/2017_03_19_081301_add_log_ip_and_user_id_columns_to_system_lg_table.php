<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogIpAndUserIdColumnsToSystemLgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('system_logs', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('ip');
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
        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('ip');
        });
    }
}
