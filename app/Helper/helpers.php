<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 22:10
 */

if ( ! function_exists('jsonResponse')) {
    function jsonResponse($message = '', $data = [], $errCode = 0)
    {
        $response = [
            'message' => $message,
            'data'    => $data,
            'errCode' => $errCode
        ];

        return response()->json($response);
    }
}

if ( ! function_exists('responseSuccess')) {
    function responseSuccess($message = '', $data = [])
    {
        return jsonResponse($message, $data, 0);
    }
}

if ( ! function_exists('responseError')) {
    function responseError($message = '', $data = [], $errCode = 1)
    {
        return jsonResponse($message, $data, $errCode);
    }
}

if ( ! function_exists('addUserLoginLog')) {
    function addUserLoginLog(\Illuminate\Http\Request $request,$userName = '')
    {
        $ip = $request->ip();
        $userName = $userName ?: \Auth::user()->name;
        $content  = "用户 '{$userName}' 登录了系统";
        \App\Models\SystemLog::addSystemLog('用户登录', 1, $content, $request->ip());
    }
}

if ( ! function_exists('addPasswordChangeLog')) {
    function addPasswordChangeLog(\Illuminate\Http\Request $request,$userName = '')
    {
        $ip = $request->ip();
        $userName = $userName ?: \Auth::user()->name;
        $content  = "用户 '{$userName}' 修改了密码";
        \App\Models\SystemLog::addSystemLog('用户修改密码', 2, $content, $request->ip());
    }
}

if ( ! function_exists('addLogOutLog')) {
    function addLogOutLog(\Illuminate\Http\Request $request,$userName = '')
    {
        $ip = $request->ip();
        $userName = $userName ?: \Auth::user()->name;
        $content  = "用户 '{$userName}' 登出了系统";
        \App\Models\SystemLog::addSystemLog('用户退出', 3, $content, $ip);
    }
}

if (!function_exists('getUserCart')) {
    function getUserCart()
    {
        $userId = \Auth::user()->id;
        if($userCart = \App\Models\ShoppingCart::where('identifier', $userId)->first()){
            return \Cart::instance($userCart->instance);
        }

        $userCartInstance = \Cart::instance();
        $userCartInstance->tax(0);
        $userCartInstance->store($userId);
        return $userCartInstance;
    }
}

if (!function_exists('cartAdd')) {
    function cartAdd()
    {
        $userId = \Auth::user()->id;

    }
}

if (!function_exists('roleList')) {
    function getRoleList()
    {
        $roles = \App\Models\Role::all();
        return $roles;
    }
}