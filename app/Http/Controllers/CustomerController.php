<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Models\Permission;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customerInfo(Request $request)
    {
        $user = \Auth::user();

        return view('customer.info', compact('user'));
    }

    public function customerUpdate($id, Request $request)
    {
        $userExist = User::where([ 'name' => $request->get('name') ])->orWhere([ 'email' => $request->get('email') ])->havingRaw("id <> '{$id}'")->get();
        if (count($userExist) > 0) {
            return responseError('用户信息已被占用！');
        }

        if ($user = User::find($id)) {
            $user->name  = $request->get('name');
            $user->email = $request->get('email');
            if ($user->save()) {
                return responseSuccess('用户信息修改成功');
            }

            return responseError('用户信息修改失败');
        }

        return responseError('未找到该用户信息');
    }

    public function userList()
    {

        $roles = Role::all()->toArray();

        $userCollection = User::with('roles')->paginate(10, [ '*' ], 'goods-list-page')->setPageName('messagePage');

        return view('user.list', compact('userCollection', 'roles'));
    }

    public function userAdd(Request $request)
    {

        if (User::where([ 'name' => $request->get('name') ])->orWhere([ 'email' => $request->get('email') ])->count() > 0) {
            return responseError('用户信息已存在！');
        }

        $user           = new User();
        $user->name     = $request->get('name');
        $user->email    = $request->get('email');
        $user->password = bcrypt('123456');

        if ($user->save()) {
            if ($request->get('role')) {
                $user->roles()->sync(array( $request->get('role') ));
            }

            return responseSuccess('添加成功');
        }

        return responseError('添加失败');
    }

    public function userEdit($roleId, Request $request)
    {

        if ( ! $user = User::with('roles')->find($roleId)) {
            // 返回未找到页面
            abort(404);
        }
        $roles = Role::all()->toArray();

        return view('user.edit', compact('roles', 'user'));
    }

    public function userUpdate($id, Request $request)
    {
        $userCount = User::where([ 'name' => $request->get('name') ])->orWhere([ 'email' => $request->get('email') ])->havingRaw("id <> '{$id}'")->get();
        if (count($userCount) > 0) {
            return responseError('用户信息已被占用');
        }

        if ($user = User::find($id)) {
            $user->name  = $request->get('name');
            $user->email = $request->get('email');
            if ($user->save()) {
                if ($request->get('role')) {
                    $user->roles()->sync(array( $request->get('role') ));
                }

                return responseSuccess('用户信息更改成功！');
            }

            return responseError('用户信息更改失败！');

        }

        return responseError('未找到该用户信息！');
    }

    public function userDelete($id, Request $request)
    {
        if ($user = User::with('roles')->find($id)) {
            if (User::where([ 'id' => $id ])->delete()) {
                $user->params && $user->detachRoles($user->roles);

                return responseSuccess('用户删除成功！');
            }

            return responseError('用户删除失败！');
        }

        return responseError('未找到该用户信息！');
    }
}