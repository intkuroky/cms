<?php
namespace App\Http\Filters;

class DeliveryOrderListFilter extends BaseFilter
{

    public $namespace = '';

    public function columns()
    {
        return [
            Column::build('order_id', 'order_no', [ $this, 'orderCallback' ]),
            Column::build('name', '', [ $this, 'nameCallback' ]),
            Column::build('status', 'status'),
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

    public function orderCallback($builder, Column $column, $value)
    {
        $builder->where('order_no', 'LIKE', '%' . $value . '%');
        return $builder;
    }

}