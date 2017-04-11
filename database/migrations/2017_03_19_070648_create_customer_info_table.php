<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customer_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phone')->comment('联系电话');
            $table->integer('user_id')->comment('用户编号');
            $table->integer('licence_no')->comment('许可证号');
            $table->string('licence_company')->comment('发证单位');
            $table->string('company_name')->comment('企业名称');
            $table->string('company_type')->comment('企业类型');
            $table->integer('tax_code')->comment('邮编');
            $table->string('supply_company')->comment('供货单位');
            $table->dateTime('invalid_time')->comment('有效期限');
            $table->string('company_address')->comment('企业地址');
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
        Schema::drop('customer_info');
    }
}
