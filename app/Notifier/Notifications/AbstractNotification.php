<?php

namespace App\Notifier\Notifications;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/4
 * Time: 23:06
 */
abstract class AbstractNotification
{

    public $debug = false;

    /**
     * 手机号
     *
     * @var
     */
    protected $to;

    /**
     * 短信内容
     *
     * @var
     */
    protected $content;

    /**
     * 短信中的参数
     * @var
     */
    protected $data;

    public function __construct()
    {
        $this->debug = config('app.debug');
    }

    function to($to)
    {
        $this->to = $to;

        return $this;
    }

    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    abstract function send();

}