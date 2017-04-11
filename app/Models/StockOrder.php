<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class StockOrder extends Model
{

    //未出库
    const ORDER_STOCKING = 1;
    //已出库
    const ORDER_SHIPPED = 2;
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
                $orderStatus = '未出库';
                break;
            case 2:
                $orderStatus = '已出库';
                break;
            case 3:
                $orderStatus = "已提交";
                break;
        }

        return $orderStatus;
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public static function getStockOrderStatusMaps()
    {
        return [
            self::ORDER_STOCKING => '未出库',
            self::ORDER_SHIPPED => '已出库',
            self::ORDER_SUBMIT => '已提交'
        ];
    }
}