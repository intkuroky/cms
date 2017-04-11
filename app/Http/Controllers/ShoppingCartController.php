<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Models\Goods;

class ShoppingCartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 购物车展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart()
    {
        $goodsCollection = Goods::query()->paginate(10, [ '*' ], 'shoppingPage')->setPageName('shoppingPage');

        return view('goods.list', compact('goodsCollection'));
    }

    /**
     * 加入购物车
     */
    public function joinCart()
    {

    }
}