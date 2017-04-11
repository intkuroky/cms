<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/7
 * Time: 12:37
 */

namespace App\Notifier\Notifications;

class TabOpenedNotification extends AbstractNotification
{

    /**
     * @return mixed
     */
    function send()
    {
        if ( ! $this->debug) {
            $data     = $this->data;
            $response = \PhpSms::make()->to($this->to)->content('卡片“'.$data['tab_name'].'”,于“'.$data['open_time'].'”被打开。')->send();
            \Log::info('Sms send to '.$this->to, [ 'content' => $this->content, 'response' => $response ]);
        }
    }

}