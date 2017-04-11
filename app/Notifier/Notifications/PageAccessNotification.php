<?php

namespace App\Notifier\Notifications;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/4
 * Time: 23:08
 */
class PageAccessNotification extends AbstractNotification
{

    public function sendContent($url = '', $accessTime = '')
    {
        $this->content = '链接“'.$url.'”,于“'.$accessTime.'”被访问。';
    }

    /**
     * @return mixed
     */
    function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return mixed
     */
    final function send()
    {
        if ( ! $this->debug) {
            $data     = $this->data;
            $response = \PhpSms::make()->to($this->to)->content('链接“'.$data['access_url'].'”,于“'.$data['access_time'].'”被访问。')->send();
            \Log::info('Sms send to '.$this->to, [ 'content' => $this->content, 'response' => $response ]);
        }
    }

}