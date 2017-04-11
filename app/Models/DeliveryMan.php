<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class DeliveryMan extends Model
{

    protected $table = 'delivery_mans';

    //空闲
    const STATUS_FREE = 1;
    //配送中
    const STATUS_DELIVERING = 2;

    protected $appends = [
        'deliveryStatus',
        'manSex'
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

    public function getManSexAttribute()
    {
        $sex = '';
        if ($this->sex == 0) {
            $sex = '男';
        } else {
            $sex = '女';
        }

        return $sex;
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }

    public static function getDeliveryManSexMaps()
    {
        return [
            0 => '男',
            1 => '女'
        ];
    }

    public static function getDeliveryManStatusMaps()
    {
        return [
            self::STATUS_FREE => '空闲中',
            self::STATUS_DELIVERING => '配送中',
        ];
    }
}