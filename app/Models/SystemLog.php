<?php namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/15
 * Time: 0:44
 */
class SystemLog extends Model
{

    protected $appends = [ 'logType', 'userDisplayName' ];

    /**
     * 添加系统日志
     *
     * @param string $title
     * @param int    $type 1 用户登陆 | 2 修改密码 | 3 用户退出
     * @param string $ip
     * @param string $content
     */
    public static function addSystemLog($title = '', $type = 0, $content = '', $ip = '')
    {

        $systemLog          = new self();
        $systemLog->title   = $title;
        $systemLog->type    = $type;
        $systemLog->content = $content;
        $systemLog->user_id = \Auth::user() ? \Auth::user()->id : 0;
        $systemLog->ip      = $ip;
        $systemLog->save();
    }

    public function getUserDisplayNameAttribute()
    {
        $user = User::find($this->user_id);

        return $user ? $user->display_name : '';
    }

    public function getLogTypeAttribute()
    {
        $logType = '';

        switch ($this->type) {
            case 1:
                $logType = "用户登陆";
                break;
            case 2:
                $logType = "用户注册";
                break;
            case 3:
                $logType = '用户退出';
                break;
            default:
                $logType = '未知';
        }

        return $logType;
    }

}