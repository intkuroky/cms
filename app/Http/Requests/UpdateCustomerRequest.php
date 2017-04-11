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
class UpdateCustomerRequest extends Request
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
            'logName' => 'required',
            'displayName' => 'required',
            'role' => 'required',
            'idCrad' => 'required',
            'phone' => 'required',
            'licenceNo' => 'required',
            'licenceCo' => 'required',
            'cName' => 'required',
            'cType' => 'required',
            'taxCode' => 'required',
            'sCompany' => 'required',
            'validTime' => 'required',
            'cAddress' => 'required',
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
            'type.required' => '用户类型是必须的',
            'logName.required' => '用户名是必须的',
            'password.required' => '用户密码是必须的',
            'role.required' => '用户角色是必须的',
            'displayName.required' => '用户名字是必须的',
            'idCrad.required' => '身份证号是必须的',
            'phone.required' => '手机号是必须的',
            'licenceNo.required' => '许可证号',
            'cName.required' => '公司名字是必须的',
            'cType.required' => '公司类型是必须的',
            'taxCode.required' => '邮编是必须的',
            'sCompany.required' => '供货单位是必须的',
            //'validTime.required' => '有效期限是必须的',
            'cAddress.required' => '企业地址是必须的',
        );
    }
}
