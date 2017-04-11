<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\SmsVerify;
use App\Notifier\Notifications\ClickRelyNotification;
use App\Notifier\Notifications\TabOpenedNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifier\Notifications\PageAccessNotification;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [ 'sendVerifyCode', 'checkVerifyCode', 'resetPasswd']
        ]);
    }

    public function index(Request $request)
    {
        return view('index');
    }

    public function sendVerifyCode(Request $request)
    {

        $phone = $request->get('phone');
        if ( ! $user = User::where('phone', $phone)->first()) {
            return responseError('手机号不存在');
        }

        $smsVerifyService = new SmsVerify();
        if ($verifyCode = $smsVerifyService->sendVerifyMessage($phone)) {
            return responseSuccess('短信验证码已发送,验证码为'.$verifyCode, $user->toArray());
        }

        return responseError('短信验证码发送失败');
    }

    public function checkVerifyCode(Request $request)
    {
        $phone      = $request->get('phone');
        $verifyCode = $request->get('verifyCode');

        if ( ! $user = User::where('phone', $phone)->first()) {
            return responseError('手机号不存在');
        }

        if (SmsVerify::checkVerifyCode($phone, $verifyCode)) {
            return responseSuccess('', $user->toArray());
        } else {
            return responseError('验证码错误或已失效');
        }
    }

    public function resetPasswd(ResetPasswordRequest $request)
    {
        $password = $request->get('passwd');
        $name = $request->get('name');


        $user = User::where('name', $name)->first();
        $user->password = bcrypt($password);

        if ($user->save()) {
            \Auth::login($user);
            return responseSuccess('密码重置成功');
        }
        return responseError('密码重置失败');
    }

}