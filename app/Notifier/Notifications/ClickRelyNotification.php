<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/7
 * Time: 12:08
 */

namespace App\Notifier\Notifications;

class ClickRelyNotification extends AbstractNotification
{

    /**
     * @return mixed
     */
    function send()
    {
        if ( ! $this->debug) {
            $data     = $this->data;
            $response = \PhpSms::make()->to($this->to)->content('操作“'.$data['action_name'].'”，的执行结果“'.$data['result'].'”。')->send();
            \Log::info('Sms send to '.$this->to, [ 'content' => $this->content, 'response' => $response ]);
        }
    }

}