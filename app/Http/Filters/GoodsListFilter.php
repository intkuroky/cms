<?php
namespace App\Http\Filters;

use App\Http\Filters\Column;
use App\Http\Filters\BaseFilter;
use App\Models\Role;
use App\Models\RoleUser;
use App\User;

class GoodsListFilter extends BaseFilter
{

    public $namespace = '';

    public function columns()
    {
        return [
            Column::build('name', '', [ $this, 'goodsNameCallBack' ]),
            Column::build('company', null, [$this, 'companyCallback']),
            Column::build('datetime', 'null', [$this, 'datetimeCallback'])
        ];
    }

    public function goodsNameCallBack($builder, Column $column, $value)
    {

        $builder->where('name', 'LIKE', '%' . $value . '%');
        return $builder;
    }

    public function datetimeCallback($builder, Column $column, $value)
    {
        list($start, $end) = explode('-', $value);

        $builder->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)))->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end)));

        return $builder;
    }

    public function companyCallback($builder, Column $column, $value)
    {
        $builder->where('supply_company','LIKE',  '%' . $value . '%');
        return $builder;
    }
    
    public function applicantColumnCallback($builder, Column $column, $value)
    {
        $builder->whereHas('merchant', function ($query) use ($value) {
            $query->where('nickname', $value);
        });

        return $builder;
    }

}