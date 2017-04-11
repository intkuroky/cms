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
class AddDelivieryManRequest extends Request
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
            'phone' => 'required|numeric',
            'idCard' => 'required',
            'sex' => 'required|numeric',
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
            'name.required' => '名字是必须的',
            'phone.required' => '手机号是必须的',
            'idCard.required' => '身份证号是必须的',
            'sex.required' => '性别是必须的',
            'sex.numeric' => '性别必须是数字',
            'phone.numeric' => '手机号必须是数字',
        );
    }
}
