<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class DeliveryCar extends Model
{

    //空闲
    const STATUS_FREE = 1;
    //配送中
    const STATUS_DELIVERING = 2;

    protected $appends = [
        'deliveryStatus'
    ];

    public function getDeliveryStatusAttribute()
    {
        $orderStatus = '';
        switch ($this->status) {
            case 1:
                $orderStatus = '空闲中';
                break;
            case 2:
                $orderStatus = '配送中';
                break;
        }

        return $orderStatus;
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }
}