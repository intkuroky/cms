<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\OrderListFilter;
use App\Models\Order;
use App\Models\Permission;
use App\Models\StockOrder;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class SellerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sellerOrder(OrderListFilter $filter, Request $request)
    {

        $filters                 = $request->all();
        $ordersCollectionBuilder = Order::with('orderGoods');
        $ordersCollectionBuilder = $filter->filter($ordersCollectionBuilder, $filters);
        $ordersCollection        = $ordersCollectionBuilder->paginate(10, [ '*' ],
            'sellerPage')->setPageName('sellerPage');

        return view('order.list', compact('ordersCollection', 'filters'));
    }

    public function sellerRejectOrder($orderId, Request $request)
    {
        if ($order = Order::find($orderId)) {
            $order->status        = Order::ORDER_APPLY_FAILURE;
            $order->reject_reason = $request->get('reject_reason');
            $order->action_seller = \Auth::user()->id;
            if ($order->save()) {
                return responseSuccess('订单已成功拒绝');
            }

            return responseError('订单拒绝失败');
        }

        return responseError('未找到该订单');
    }

    public function sellerAdoptOrder($orderId, Request $request)
    {
        if ($order = Order::find($orderId)) {
            $order->status        = Order::ORDER_APPLY_SUCCESS;
            $order->action_seller = \Auth::user()->id;
            //订单状态改为未签收
            $order->deliver_status = Order::ORDER_DELIVERING;
            if ($order->save()) {

                //生成备货单
                $stockOrder           = new StockOrder();
                $stockOrder->order_no = 'BH'.$order->order_no;
                $stockOrder->status   = StockOrder::ORDER_STOCKING;
                $stockOrder->order_id = $orderId;
                if ($stockOrder->save()) {
                    return responseSuccess('订单已成功接受');
                }
            }

            return responseError('订单接受失败');
        }

        return responseError('未找到该订单');
    }

}