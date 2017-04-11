<?php namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class Order extends Model
{

    //申请中
    const ORDER_APPLYING = 1;
    //订单申请成功
    const ORDER_APPLY_SUCCESS = 2;
    //订单申请失败
    const ORDER_APPLY_FAILURE = 3;

    //配送状态
    //未签收
    const ORDER_DELIVERING = 1;
    //已签收
    const ORDER_RECEIVED = 2;
    //已评价
    const ORDER_COMMENTED = 3;
    //订单已完成
    const ORDER_COMPLETE = 4;

    protected $appends = [
        'orderStatus',
        'orderDeliveryStatus'
    ];

    public static function getOrderStatusMaps()
    {
        return [
            self::ORDER_APPLYING => '申请中',
            self::ORDER_APPLY_SUCCESS => '申请成功',
            self::ORDER_APPLY_FAILURE => '申请失败',
        ];
    }

    public static function getDeliveryStatusMaps()
    {
        return [
            self::ORDER_DELIVERING => '未签收',
            self::ORDER_RECEIVED => '已签收',
            self::ORDER_COMMENTED => '已评价',
            self::ORDER_COMPLETE => '已完成',
        ];
    }

    public function getOrderDeliveryStatusAttribute()
    {
        $orderDeliveryStatus = '';
        switch ($this->deliver_status) {
            case 1:
                $orderDeliveryStatus = '未签收';
                break;
            case 2:
                $orderDeliveryStatus = '已签收';
                break;
            case 3:
                $orderDeliveryStatus = "已评价";
                break;
            case 4:
                $orderDeliveryStatus = "已完成";
                break;
        }

        return $orderDeliveryStatus;
    }

    public function getOrderStatusAttribute()
    {
        $orderStatus = '';
        switch ($this->status) {
            case 1:
                $orderStatus = '申请中';
                break;
            case 2:
                $orderStatus = '申请成功';
                break;
            case 3:
                $orderStatus = "申请失败";
                break;

        }

        return $orderStatus;
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'id');
    }

    public function orderUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}