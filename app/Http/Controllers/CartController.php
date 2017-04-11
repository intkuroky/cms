<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Permission;
use App\Models\ShoppingCart;
use App\User;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Pagination\Paginator;
use Illuminate\Session\SessionManager;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function testCart(Request $request, Application $app)
    {
        //$userId = \Auth::user()->id;
        //$cart   = new Cart($app->make('session'), $app->make('events'));
        //$cart->add([
        //    'id'      => 1,
        //    'name'    => 'Test cart',
        //    'qty'     => 1,
        //    'price'   => 1.00,
        //    'options' => [ 'size' => 'large' ]
        //], [
        //    'id'      => 2,
        //    'name'    => 'Test cart',
        //    'qty'     => 3,
        //    'price'   => 2.00,
        //    'options' => [ 'color' => 'red' ]
        //]);
        ////$cart->update(1, 2);
        //dump($cart->content());
        //dump($cart->search(function ($item, $rowId) {
        //
        //}));
        //$cart->store($userId);

        //$userCart     = ShoppingCart::where('identifier', $userId)->first();
        //$cartInstance = \Cart::instance($userCart->instance);
        //dump($cartInstance->destroy());
        //dump($cartInstance->restore($userId));
        //$cart = \Cart::add(['id' => 1, 'name' => 'Test cart', 'qty' => 1, 'price' => 1.00, 'options' => ['size' => 'large']]);
        //dump($cart);
        //$rowId = '064c8a2fda8451c8c23926d6d3592023';
        //$cart = \Cart::update($rowId, 2);
        //dump($cart);
        //dump(Cart::store(\Auth::user()->id));
    }

    public function joinCart(Request $request)
    {

        $userId   = \Auth::user()->id;
        $goodsId  = $request->get('id');
        $goodsQty = $request->get('qty');

        $goodsModel = Goods::find($goodsId);
        $goodsName  = $goodsModel->name;
        $price      = $goodsModel->price;

        if ( ! $cartInstance = getUserCart()) {
            return responseError('获取购物车实例失败');
        }

        $cartSearchItemCollection = $cartInstance->search(function ($cartItem, $rowId) use ($goodsId) {
            return $cartItem->id === $goodsId;
        });

        // 查找已有购物车
        $itemRowId = 0;
        $qty       = 1;
        foreach ($cartSearchItemCollection as $cartSearchItem) {
            $itemRowId = $cartSearchItem->rowId;
            $qty       = $cartSearchItem->qty + $goodsQty;
        }

        //当已有购物车存在时，更新已有购物车
        //当改购物车不存在时，创建购物车
        if ($itemRowId) {
            $result = $cartInstance->update($itemRowId, $qty) ? true : false;
        } else {
            $result = $cartInstance->add($goodsId, $goodsName, $goodsQty, $price)->associate('Goods') ? true : false;
        }

        if ($result) {
            // 删除旧的购物车新的购物车加入数据库
            ShoppingCart::where('identifier', $userId)->delete();
            \Cart::store(\Auth::user()->id);

            //计算购物车总数
            $count = $cartInstance->count();
            $price = $cartInstance->total();

            $cartInfo = [ 'num' => $count, 'price' => $price ];

            return responseSuccess('商品已成功加入购物车', $cartInfo);
        }

        return responseError('商品未成功加入购物车');
    }

    public function cartList()
    {
        $cartCollections = getUserCart();

        return view('cart.list', compact('cartCollections'));
    }

    public function cartRemove(Request $request)
    {
        $userId  = \Auth::user()->id;
        $goodsId = $request->get('id', 0);

        if ( ! $cartInstance = getUserCart()) {
            return responseError('获取购物车实例失败');
        }

        $cartSearchItemCollection = $cartInstance->search(function ($cartItem, $rowId) use ($goodsId) {
            return $cartItem->id === $goodsId;
        });

        // 查找已有购物车
        $itemRowId = 0;
        foreach ($cartSearchItemCollection as $cartSearchItem) {
            $itemRowId = $cartSearchItem->rowId;
        }

        //当已有购物车存在时，更新已有购物车
        //当改购物车不存在时，创建购物车
        if ($itemRowId) {
            $cartInstance->remove($itemRowId);
            // 删除旧的购物车新的购物车加入数据库
            ShoppingCart::where('identifier', $userId)->delete();
            \Cart::store(\Auth::user()->id);

            return responseSuccess('商品成功移除购物车');
        }

        return responseError('购物车中没有该商品');
    }

    public function cartClear(Request $request)
    {
        $userId  = \Auth::user()->id;
        $goodsId = $request->get('id', 0);

        if ( ! $cartInstance = getUserCart()) {
            return responseError('获取购物车实例失败');
        }

        $cartInstance->destroy();
        ShoppingCart::where('identifier', $userId)->delete();
        \Cart::store(\Auth::user()->id);

        return responseSuccess('购物车已清空');
    }

    public function cartReduce(Request $request)
    {
        $userId = \Auth::user()->id;
        $goodsId  = $request->get('id');
        $goodsQty = $request->get('qty', 1);

        if (!$goodsModel = Goods::find($goodsId)) {
            return responseError('未找到该商品');
        }

        $price     = $goodsModel->price;
        $goodsName = $goodsModel->name;

        if ( ! $cartInstance = getUserCart()) {
            return responseError('获取购物车实例失败');
        }

        $cartSearchItemCollection = $cartInstance->search(function ($cartItem, $rowId) use ($goodsId) {
            return $cartItem->id === $goodsId;
        });

        // 查找已有购物车
        $itemRowId = 0;
        $qty       = 1;
        foreach ($cartSearchItemCollection as $cartSearchItem) {
            $itemRowId = $cartSearchItem->rowId;
            $qty       = $cartSearchItem->qty - $goodsQty;
        }

        //当已有购物车存在时，更新已有购物车
        //当改购物车不存在时，创建购物车
        if ($itemRowId) {
            $result = $cartInstance->update($itemRowId, $qty) ? true : false;
        } else {
            $result = $cartInstance->add($goodsId, $goodsName, $goodsQty, $price)->associate('Goods') ? true : false;
        }

        if ($qty == 0 || $result) {
            // 删除旧的购物车新的购物车加入数据库
            ShoppingCart::where('identifier', $userId)->delete();
            \Cart::store(\Auth::user()->id);

            //计算购物车总数
            $count = $cartInstance->count();
            $price = $cartInstance->total();

            $cartInfo = [ 'num' => $count, 'price' => $price ];

            return responseSuccess('商品已成功加入购物车', $cartInfo);
        }

        return responseError('商品未成功加入购物车');
    }

    public function cartIncrease(Request $request)
    {

        $userId = \Auth::user()->id;
        $goodsId  = $request->get('id');
        $goodsQty = $request->get('qty', 1);

        if (!$goodsModel = Goods::find($goodsId)) {
            return responseError('未找到该商品');
        }

        $price     = $goodsModel->price;
        $goodsName = $goodsModel->name;

        if ( ! $cartInstance = getUserCart()) {
            return responseError('获取购物车实例失败');
        }

        $cartSearchItemCollection = $cartInstance->search(function ($cartItem, $rowId) use ($goodsId) {
            return $cartItem->id === $goodsId;
        });

        // 查找已有购物车
        $itemRowId = 0;
        $qty       = 1;
        foreach ($cartSearchItemCollection as $cartSearchItem) {
            $itemRowId = $cartSearchItem->rowId;
            $qty       = $cartSearchItem->qty + $goodsQty;
        }

        //当已有购物车存在时，更新已有购物车
        //当改购物车不存在时，创建购物车
        if ($itemRowId) {
            $result = $cartInstance->update($itemRowId, $qty) ? true : false;
        } else {
            $result = $cartInstance->add($goodsId, $goodsName, $goodsQty, $price)->associate('Goods') ? true : false;
        }

        if ($result) {
            // 删除旧的购物车新的购物车加入数据库
            ShoppingCart::where('identifier', $userId)->delete();
            \Cart::store(\Auth::user()->id);

            //计算购物车总数
            $count = $cartInstance->count();
            $price = $cartInstance->total();

            $cartInfo = [ 'num' => $count, 'price' => $price ];

            return responseSuccess('商品已成功加入购物车', $cartInfo);
        }

        return responseError('商品未成功加入购物车');
    }

    public function cartAdjust(Request $request)
    {

        $userId = \Auth::user()->id;
        $goodsId  = $request->get('id');
        $goodsQty = $request->get('qty', 1);

        if (!$goodsModel = Goods::find($goodsId)) {
            return responseError('未找到该商品');
        }

        $price     = $goodsModel->price;
        $goodsName = $goodsModel->name;

        if ( ! $cartInstance = getUserCart()) {
            return responseError('获取购物车实例失败');
        }

        $cartSearchItemCollection = $cartInstance->search(function ($cartItem, $rowId) use ($goodsId) {
            return $cartItem->id === $goodsId;
        });

        // 查找已有购物车
        $itemRowId = 0;
        $qty       = 1;
        foreach ($cartSearchItemCollection as $cartSearchItem) {
            $itemRowId = $cartSearchItem->rowId;
            $qty       = $goodsQty;
        }

        //当已有购物车存在时，更新已有购物车
        //当改购物车不存在时，创建购物车
        if ($itemRowId) {
            $result = $cartInstance->update($itemRowId, $qty) ? true : false;
        } else {
            $result = $cartInstance->add($goodsId, $goodsName, $goodsQty, $price)->associate('Goods') ? true : false;
        }

        if ($result) {
            // 删除旧的购物车新的购物车加入数据库
            ShoppingCart::where('identifier', $userId)->delete();
            \Cart::store(\Auth::user()->id);

            //计算购物车总数
            $count = $cartInstance->count();
            $price = $cartInstance->total();

            $cartInfo = [ 'num' => $count, 'price' => $price ];

            return responseSuccess('商品已成功加入购物车', $cartInfo);
        }

        return responseError('商品未成功加入购物车');
    }
}