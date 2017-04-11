<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role;

class PermissionController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function permissionList()
    {

        $permissionCollection = Permission::paginate(10, [ '*' ], 'page')->setPageName('page');

        return view('permission.list', compact('permissionCollection'));
    }

    public function permissionAdd(Request $request)
    {

        if (Permission::where([ 'name' => $request->get('name') ])->count() > 0) {
            return responseError('权限名称已存在！');
        }

        $permission               = new Permission();
        $permission->name         = $request->get('name');
        $permission->display_name = $request->get('name');
        $permission->description  = $request->get('description');

        if ($permission->save()) {
            return responseSuccess('添加成功');
        }

        return responseError('添加失败');
    }

    public function permissionEdit($permissionId, Request $request)
    {
        if ( ! $permission = Permission::find($permissionId)) {
            // 返回未找到页面
            abort(404);
        }

        return view('permission.edit', compact('permission'));
    }

    public function permissionUpdate($id, Request $request)
    {

        if ($permission = Permission::find($id)) {
            $permission->name        = $request->get('name');
            $permission->description = $request->get('description');
            if ($permission->save()) {
                return responseSuccess('权限信息更改成功！');
            }

            return responseError('权限信息更改失败！');

        }

        return responseError('未找到该权限信息！');
    }

    public function permissionDelete($id, Request $request)
    {
        if ($permission = Permission::find($id)) {
            if (Permission::where([ 'id' => $id ])->delete()) {
                return responseSuccess('权限删除成功！');
            }

            return responseError('权限删除失败！');
        }

        return responseError('未找到该权限信息！');
    }
}