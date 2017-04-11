<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class DeliveryOrder extends Model
{

    //未配送
    const ORDER_DELIVERY_NONE = 1;
    //配送中
    const ORDER_DELIVERING = 2;
    //已配送
    const ORDER_DELIVERED = 3;

    protected $appends = [
        'orderStatus'
    ];

    public static function getDeliveryOrderStatusMaps()
    {
        return [
            self::ORDER_DELIVERY_NONE => '未配送',
            self::ORDER_DELIVERING => '配送中',
            self::ORDER_DELIVERED => '已配送'
        ];
    }

    public function getOrderStatusAttribute()
    {
        $orderStatus = '';
        switch ($this->status) {
            case 1:
                $orderStatus = '未配送';
                break;
            case 2:
                $orderStatus = '配送中';
                break;
            case 3:
                $orderStatus = '已配送';
                break;
        }

        return $orderStatus;
    }

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }
}