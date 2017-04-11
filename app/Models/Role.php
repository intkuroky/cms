<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class Role extends EntrustRole
{

    // 系统
    const ROLE_ADMIN = 'admin';

    // 客户
    const ROLE_CUSTOMER = 'customer';

    //销售
    const ROLE_SELLER = 'seller';

    //配送
    const ROLE_DELIVER = 'deliver';

    //仓库
    const ROLE_STORE = 'store';

    //分拣
    const ROLE_PICKER = 'picker';

    protected $appends = [
        //'permission'
    ];

    public function getPermissionAttribute()
    {
        //$permissions = Permission::where([''])
        //if(){
        //
        //}
    }
}