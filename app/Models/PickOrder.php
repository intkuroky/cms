<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class PickOrder extends Model
{

    //未分拣
    const ORDER_UNPICK = 1;
    //已分拣
    const ORDER_PICKED = 2;
    //已提交
    const ORDER_SUBMIT = 3;

    protected $appends = [
        'orderStatus'
    ];


    public function getOrderStatusAttribute()
    {
        $orderStatus = '';
        switch ($this->status) {
            case 1:
                $orderStatus = '未分拣';
                break;
            case 2:
                $orderStatus = '已分拣';
                break;
            case 3:
                $orderStatus = '已提交';
                break;
        }

        return $orderStatus;
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }
}