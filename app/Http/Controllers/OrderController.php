<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\OrderListFilter;
use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Permission;
use App\Models\ShoppingCart;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orderInfo($orderId, Request $request)
    {

        if ( ! $order = Order::with('orderGoods', 'orderUser', 'orderUser.customerInfo')->find($orderId)) {
            abort(404);
        }

        $user = \Auth::user();

        $orderUser = $order->orderUser ?: null;

        return view('order.info', compact('order', 'user', 'orderUser'));
    }

    public function customerUpdate($id, Request $request)
    {
        $userExist = User::where([ 'name' => $request->get('name') ])->orWhere([ 'email' => $request->get('email') ])->havingRaw("id <> '{$id}'")->get();
        if (count($userExist) > 0) {
            return responseError('用户信息已被占用！');
        }

        if ($user = User::find($id)) {
            $user->name  = $request->get('name');
            $user->email = $request->get('email');
            if ($user->save()) {
                return responseSuccess('用户信息修改成功');
            }

            return responseError('用户信息修改失败');
        }

        return responseError('未找到该用户信息');
    }

    public function orderList(OrderListFilter $filter, Request $request)
    {
        $filters = $request->all();

        $userId                  = \Auth::user()->id;
        $ordersCollectionBuilder = Order::with('orderGoods');
        $ordersCollectionBuilder->where('user_id', $userId);

        $ordersCollectionBuilder = $filter->filter($ordersCollectionBuilder, $filters);

        $ordersCollection = $ordersCollectionBuilder->paginate(10, [ '*' ],
            'order-list-page')->setPageName('orderPage');

        return view('order.list', compact('ordersCollection', 'filters'));
    }

    public function orderConfirm($goodsId, $quantity = 1, Request $request)
    {

        $goodsCollections = Goods::whereIn('id', [ $goodsId ])->get();

        return view('order.confirm', compact('goodsCollections', 'quantity'));
    }

    public function orderReceived($orderId, Request $request)
    {
        if ( ! $order = Order::find($orderId)) {
            return responseError('未找到该订单');
        }

        Order::where([ 'id' => $orderId ])->update([ 'deliver_status' => Order::ORDER_RECEIVED ]);

        return responseSuccess('确认收货成功');
    }

    public function orderComment($orderId, Request $request)
    {
        $comment = $request->get('comment');

        if ( ! $order = Order::find($orderId)) {
            return responseError('未找到该订单');
        }

        Order::where([ 'id' => $orderId ])->update([
            'deliver_status' => Order::ORDER_COMMENTED,
            'comments'       => $comment
        ]);

        return responseSuccess('订单评价成功');
    }

    public function orderCreate(Request $request)
    {

        $userId    = \Auth::user()->id;
        $order     = new Order();
        $goodsData = $request->get('data');

        $order->order_no       = date('YmdHis');
        $order->price          = $request->get('total');
        $order->user_id        = $userId;
        $order->status         = Order::ORDER_APPLYING;
        $order->deliver_status = Order::ORDER_DELIVERING;

        $result = true;
        if ($order->save()) {
            foreach ($goodsData as $goods) {
                $goodsModel = Goods::find($goods['goodsId']);

                $orderGoods             = new OrderGoods();
                $orderGoods->goods_id   = $goods['goodsId'];
                $orderGoods->goods_name = $goodsModel->name;
                $orderGoods->order_id   = $order->id;
                $orderGoods->price      = $goodsModel->price;
                $orderGoods->quantity   = $goods['qty'];
                $orderGoods->img        = $goodsModel['img'];
                $result                 = $orderGoods->save() ? $result : false;
            }

            if ($result) {
                // 清空购物车
                $userCart = getUserCart();
                $userCart->destroy();
                ShoppingCart::where('identifier', $userId)->delete();
                $userCart->store($userId);

                return responseSuccess('订单申请成功');
            }
        }

        return responseError('订单申请失败');
    }

}