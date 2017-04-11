<?php namespace App\Http\Requests;
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/3/19
 * Time: 13:07
 */

use App\Http\Requests\Request;

/**
 * Request的样例
 * Class StoreRoleRequest
 * @package App\Http\Requests\Store
 */
class UpdateManagerRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array(
            'type' => 'required',
            'phone' => 'required',
            'logName' => 'required',
            'displayName' => 'required',
            'role' => 'required',
            'idCrad' => 'required'
        );
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return array(
            'type.required' => '企业类型是必须的',
            'phone.required' => '手机号是必须的',
            'logName.required' => '用户名是必须的',
            'password.required' => '用户密码是必须的',
            'role.required' => '用户角色是必须的',
            'displayName.required' => '姓名是必须的',
            'idCrad.required' => '身份证号是必须的'
        );
    }
}
