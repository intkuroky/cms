<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\UserListFilter;
use App\Http\Requests\AddCustomerRequest;
use App\Http\Requests\AddManagerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Models\Permission;
use App\Models\CustomerInfo;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userList(UserListFilter $filter, Request $request)
    {

        $roles = Role::all()->toArray();

        $filters = $request->all();

        $userCollectionBuilder = User::with('roles');
        $userCollectionBuilder = $filter->filter($userCollectionBuilder, $filters);
        $userCollection = $userCollectionBuilder->paginate(10, [ '*' ], 'userPage')->setPageName('userPage');

        return view('user.list', compact('userCollection', 'roles', 'filters'));
    }

    public function userInfo($userId = '')
    {
        $userId = $userId ?: \Auth::user()->id;
        if ( ! $user = User::with('roles')->find($userId)) {
            // 返回未找到页面
            abort(404);
        }
        $roles = Role::all()->toArray();

        $userRoles = $user->roles ? $user->roles->toArray() : '无';
        $userRole = array_shift($userRoles);
        $userRoleName = $userRole['name'];

        return view('user.info', compact('user', 'roles', 'userRoleName'));
    }

    public function userAdd(AddCustomerRequest $request)
    {

        if (User::where([ 'name' => $request->get('logName') ])->orWhere('phone', $request->get('phone'))->orWhere([ 'email' => $request->get('email') ])->count() > 0) {
            return responseError('用户信息已存在！');
        }

        $userModel               = new User();
        $userModel->name         = $request->get('logName');
        $userModel->email        = $request->get('logName');
        $userModel->password     = bcrypt($request->get('password'));
        $userModel->display_name = $request->get('displayName');
        $userModel->id_card_no   = $request->get('idCrad');
        $userModel->phone   = $request->get('phone');
        if ($userModel->save()) {

            $request->has('role') && $userModel->roles()->sync([ $request->get('role') ]);

            $userInfo                  = new CustomerInfo();
            $userInfo->phone           = $request->get('phone');
            $userInfo->user_id         = $userModel->id;
            $userInfo->licence_no      = $request->get('licenceNo');
            $userInfo->licence_company = $request->get('licenceCo');
            $userInfo->company_name    = $request->get('cName');
            $userInfo->company_type    = $request->get('cType');
            $userInfo->tax_code        = $request->get('taxCode');
            $userInfo->supply_company  = $request->get('sCompany');
            $userInfo->invalid_time    = $request->get('validTime');
            $userInfo->company_address = $request->get('cAddress');

            if ($userInfo->save()) {
                return responseSuccess('用户创建成功');
            }
        }

        return responseError('用户信息保存失败');
    }

    public function managerAdd(AddManagerRequest $request)
    {
        if (User::where([ 'name' => $request->get('logName') ])->orWhere('phone', $request->get('phone'))->orWhere([ 'email' => $request->get('email') ])->count() > 0) {
            return responseError('用户信息已存在！');
        }

        $userModel               = new User();
        $userModel->name         = $request->get('logName');
        $userModel->email        = $request->get('logName');
        $userModel->password     = bcrypt($request->get('password'));
        $userModel->display_name = $request->get('displayName');
        $userModel->id_card_no   = $request->get('idCrad');
        $userModel->phone   = $request->get('phone');
        if ($userModel->save()) {
            $request->has('role') && $userModel->roles()->sync([ $request->get('role') ]);

            return responseSuccess('企业用户创建成功');
        }

        return responseError('企业用户创建失败');
    }

    public function userEdit($roleId, Request $request)
    {
        if ( ! $user = User::with([ 'roles', 'customerInfo' ])->find($roleId)) {
            // 返回未找到页面
            abort(404);
        }
        $roles = Role::all()->toArray();

        $userRole = '';
        if ( ! empty($userCollection = $user->roles)) {
            $userRole = $userCollection[0]['id'];
        }

        $customerRole = Role::where('name', 'customer')->first();

        return view('user.edit', compact('roles', 'user', 'userRole', 'customerRole'));
    }

    public function userUpdate($id, Request $request)
    {

        $userCount = User::where([ 'name' => $request->get('name') ])->orWhere('phone', $request->get('phone'))->orWhere([ 'email' => $request->get('email') ])->havingRaw("id <> '{$id}'")->get();
        if (count($userCount) > 0) {
            return responseError('用户信息已被占用');
        }

        if ($request->get('type') == 'customer') {
            $requestValidator = new UpdateCustomerRequest();
            $this->validate($request, $requestValidator->rules(), $requestValidator->messages());
        } else {
            $requestValidator = new UpdateManagerRequest();
            $this->validate($request, $requestValidator->rules(), $requestValidator->messages());
        }

        if ($user = User::find($id)) {
            $user->name         = $request->get('logName');
            $user->display_name = $request->get('displayName');
            $user->id_card_no   = $request->get('idCrad');
            $user->phone   = $request->get('phone');
            if ($user->save()) {
                $request->has('role') && $user->roles()->sync(array( $request->get('role') ));
                if ($request->get('type') == 'customer') {

                    $userInfo = CustomerInfo::where('user_id', $user->id)->first() ?: ( new CustomerInfo() );

                    //$userInfo->phone           = $request->get('phone');
                    $userInfo->user_id         = $user->id;
                    $userInfo->licence_no      = $request->get('licenceNo');
                    $userInfo->licence_company = $request->get('licenceCo');
                    $userInfo->company_name    = $request->get('cName');
                    $userInfo->company_type    = $request->get('cType');
                    $userInfo->tax_code        = $request->get('taxCode');
                    $userInfo->supply_company  = $request->get('sCompany');
                    $userInfo->invalid_time    = $request->get('validTime');
                    $userInfo->company_address = $request->get('cAddress');
                    $userInfo->save();
                }

                return responseSuccess('用户信息更改成功！');
            }

            return responseError('用户信息更改失败！');

        }

        dd($request->all());

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