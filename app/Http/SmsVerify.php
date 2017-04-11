<?php namespace App\Http;

/**
 * 短信验证
 *
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/5
 * Time: 13:44
 */
class SmsVerify
{

    private $debug = true;

    /**
     * 检查 验证码是否正确
     *
     * @param $verifyMobile
     * @param $verifyCode
     *
     * @return bool
     */
    public static function checkVerifyCode($verifyMobile, $verifyCode)
    {
        $verifyData = self::getVerifyData($verifyMobile);    // 从缓存获取验证码信息

        if ($verifyData) {
            $verifyDataInfo = json_decode($verifyData->data, true);

            if ((string)$verifyDataInfo['code'] == $verifyCode && $verifyData->expired_time >= time()) {
                return true;
            }
        }

        return false;
    }

    /**
     * 发送手机验证码
     *
     * @param $verifyPhone
     *
     * @return bool | string
     */
    public function sendVerifyMessage($verifyPhone)
    {
        $verifyCode = rand(100000, 999999);

        $smsContent = '您的短信验证码为'.$verifyCode.'，有效期为5分钟。';

        if ($this->debug) {
            $this->storeVerifyMessage($verifyPhone, $verifyCode, $smsContent);

            return $this->debug ? $verifyCode : true;
        }

        //屏蔽细节错误
        return '验证码发送失败，请稍后重试!';
    }

    /**
     * 数据库记录 短信验证码发送记录
     *
     * @param $mobile
     * @param $code
     * @param $content
     *
     * @return bool
     */
    private function storeVerifyMessage($mobile, $code, $content)
    {
        $expiredTime = $this->getVerifyCodeExpiredTime();

        $SmsRecordModel               = new \App\Models\SmsRecord();
        $SmsRecordModel->to           = $mobile;
        $SmsRecordModel->data         = json_encode([ 'mobile' => $mobile, 'code' => $code ]);
        $SmsRecordModel->content      = $content;
        $SmsRecordModel->expired_time = $expiredTime;

        if ($SmsRecordModel->save()) {    //将验证码信息写入数据库
            return true;
        }

        return false;
    }

    /**
     * 取得 验证码有效期时间戳
     *
     * @return String
     */
    private function getVerifyCodeExpiredTime()
    {
        return time() + 60 * 5;
    }

    /**
     * 获取短信验证码 先从session中取 然后数据库
     *
     * @param $mobile
     *
     * @return mixed|null
     */
    private static function getVerifyData($mobile)
    {
        if (empty($mobile)) {
            return null;
        }

        if ($result = \App\Models\SmsRecord::where('to', $mobile)->orderBy('created_at', 'desc')->first()) {
            return $result;
        }

        return null;
    }
}