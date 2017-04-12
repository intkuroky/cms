<?php
namespace App\Http\Filters;

class DeliveryManListFilter extends BaseFilter
{

    public $namespace = '';

    public function columns()
    {
        return [
            Column::build('order_id', 'order_no'),
            Column::build('name', 'name', [ $this, 'deliveryNameCallBack' ]),
            Column::build('phone', 'phone'),
            Column::build('status', 'status'),
            Column::build('id_card_no', 'id_card_no'),
            Column::build('sex', 'sex'),
            Column::build('datetime', '', [$this, 'datetimeCallback'])
        ];
    }

    public function nameCallback($builder, Column $column, $value)
    {
        $builder->whereHas('order.orderUser', function ($query) use($value) {
            $query->where('display_name', $value);
        });
        return $builder;
    }

    public function datetimeCallback($builder, Column $column, $value)
    {
        list($start, $end) = explode('-', $value);

        $builder->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)))->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end)));

        return $builder;
    }

    public function deliveryNameCallBack($builder, Column $column, $value)
    {
        $builder->where('name', 'LIKE', '%' . $value . '%');
        return $builder;
    }

}