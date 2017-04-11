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
class AddGoodsRequest extends Request
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
            'price' => 'required|numeric',
            'storeNum' => 'required|numeric',
            'barCode' => 'required',
            'supplyCompany' => 'required',
            'goodsImg' => 'required',
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
            'name.required' => '商品名称是必须的',
            'price.required' => '商品价格是必须的',
            'price.numeric' => '商品价格必须是数字',
            'storeNum.required' => '商品库存是必须的',
            'storeNum.numeric' => '商品库存必须是数字',
            'barCode.required' => '条形码是必须的',
            'supplyCompany.required' => '生产厂商是必须的',
            'goodsImg.required' => '商品图片是必须的'
        );
    }
}
