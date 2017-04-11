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
class ResetPasswordRequest extends Request
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
            'name' => 'required',
            'passwd' => 'required'
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
            'name.required' => '用户类型是必须的',
            'passwd.required' => '用户密码是必须的',
        );
    }
}
