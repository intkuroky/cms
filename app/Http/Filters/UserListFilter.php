<?php
namespace App\Http\Filters;

use App\Http\Filters\Column;
use App\Http\Filters\BaseFilter;
use App\Models\Role;
use App\Models\RoleUser;
use App\User;

class UserListFilter extends BaseFilter
{

    public $namespace = '';

    public function columns()
    {
        return [
            Column::build('search-name', '', [ $this, 'userNameCallBack' ]),
            Column::build('search-role', null, [$this, 'userRoleCallback']),
            Column::build('search-datetime', 'null', [$this, 'datetimeCallback'])
        ];
    }

    public function userNameCallBack($builder, Column $column, $value)
    {
        $userIdCollection = User::where('name', $value)->orWhere('display_name', $value)->get();
        $userIds = [];
        foreach ($userIdCollection as $user) {
            $userIds[] = $user['id'];
        }

        $builder->whereIn('id', $userIds);
        return $builder;
    }

    public function datetimeCallback($builder, Column $column, $value)
    {
        list($start, $end) = explode('-', $value);

        $builder->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)))->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end)));

        return $builder;
    }

    public function userRoleCallback($builder, Column $column, $value)
    {
        $userIdCollection = RoleUser::where('role_id', $value)->get(['user_id']);

        $userIds = [];
        foreach ($userIdCollection as $user) {
            $userIds[] = $user['user_id'];
        }
        $builder->whereIn('id', $userIds);
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