<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\StockOrderListFilter;
use App\Models\Order;
use App\Models\Permission;
use App\Models\PickOrder;
use App\Models\StockOrder;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class StockController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customerInfo(Request $request)
    {
        $user = \Auth::user();

        return view('customer.info', compact('user'));
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

    public function stockOrder(StockOrderListFilter $filter, Request $request)
    {

        $filters = $request->all();

        $pageTitle               = '出库管理';
        $ordersCollectionBuilder = StockOrder::with('orderGoods', 'order', 'order.orderUser');
        $ordersCollectionBuilder = $filter->filter($ordersCollectionBuilder, $filters);
        $ordersCollection        = $ordersCollectionBuilder->paginate(10, [ '*' ], 'orderPage');

        return view('stock.list', compact('ordersCollection', 'pageTitle', 'filters'));
    }

    public function orderUpdate($orderId, $status, Request $request)
    {
        if ($order = StockOrder::find($orderId)) {
            $order->status = $status;

            if ($order->save()) {

                // 生成拣货单
                if ($status == StockOrder::ORDER_SUBMIT) {
                    $pickOrder           = new PickOrder();
                    $pickOrder->order_no = 'JH'.date('YmdHis');
                    $pickOrder->status   = PickOrder::ORDER_UNPICK;
                    $pickOrder->order_id = $order->order_id;
                    $pickOrder->save();
                }

                if ($status == 2) {
                    return responseSuccess('订单出库成功');
                } else {
                    return responseSuccess('订单提交成功');
                }
            }

            return responseSuccess('订单状态更新失败');
        }

        return responseError('未找到订单信息');
    }

}