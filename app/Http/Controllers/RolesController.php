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

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function rolesList()
    {

        $permissions = Permission::all()->toArray();

        $rolesCollection = Role::with('perms')->paginate(10, [ '*' ], 'rolePage')->setPageName('rolePage');

        return view('roles.list', compact('rolesCollection', 'permissions'));
    }

    public function rolesAdd(Request $request)
    {

        if (Role::where([ 'name' => $request->get('name') ])->count() > 0) {
            return responseError('角色名称已存在！');
        }

        $role               = new Role();
        $role->name         = $request->get('name');
        $role->display_name = $request->get('displayName');
        $role->description  = $request->get('description');

        if ($role->save()) {
            if($request->has('permission')){
                $role->perms()->sync($request->get('permission'));
            }

            return responseSuccess('添加成功');
        }

        return responseError('添加失败');
    }

    public function rolesEdit($roleId, Request $request)
    {
        if ( ! $role = Role::with('perms')->find($roleId)) {
            // 返回未找到页面
            abort(404);
        }
        $permissions = Permission::all()->toArray();

        return view('roles.edit', compact('permissions', 'role'));
    }

    public function rolesUpdate($id, Request $request)
    {

        if ($role = Role::find($id)) {
            $role->name        = $request->get('name');
            $role->description = $request->get('description');
            if ($role->save()) {
                if($request->has('permission')){
                    $role->perms()->sync($request->get('permission'));
                }
                return responseSuccess('角色信息更改成功！');
            }

            return responseError('角色信息更改失败！');

        }

        return responseError('未找到该角色信息！');
    }

    public function rolesDelete($id, Request $request)
    {
        if ($role = Role::with('perms')->find($id)) {
            if (Role::where([ 'id' => $id ])->delete()) {
                $role->params && $role->detachPermissions($role->perms);

                return responseSuccess('角色删除成功！');
            }

            return responseError('角色删除失败！');
        }

        return responseError('未找到该角色信息！');
    }
}