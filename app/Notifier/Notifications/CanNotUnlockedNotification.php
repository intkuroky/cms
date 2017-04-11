<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/7
 * Time: 13:16
 */

namespace App\Notifier\Notifications;

use Carbon\Carbon;

class CanNotUnlockedNotification extends AbstractNotification
{

    /**
     * @return mixed
     */
    function send()
    {
        if ( ! $this->debug) {
            $data     = $this->data;
            $datetime = Carbon::now();
            $response = \PhpSms::make()->to('15236821650')->content('页面“' . $datetime . '”发生异常，不能解锁。')->send();
            \Log::info('Sms send to '.$this->to, [ 'content' => $this->content, 'response' => $response ]);
        }
    }

}