<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\Order;
use App\Models\Permission;
use App\Models\PickOrder;
use App\Models\StockOrder;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class PickController extends Controller
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

    public function unpickOrder()
    {
        $pageTitle        = '未分拣订单';
        $ordersCollection = PickOrder::with('orderGoods')->where('status', PickOrder::ORDER_UNPICK)->paginate(10,
            [ '*' ], 'order-list-page')->setPageName('orderPage');

        return view('pick.list', compact('ordersCollection', 'pageTitle'));
    }

    public function pickedOrder()
    {
        $pageTitle        = '已分拣订单';
        $ordersCollection = PickOrder::with('orderGoods')->whereIn('status',
            [ PickOrder::ORDER_PICKED, PickOrder::ORDER_SUBMIT ])->paginate(10, [ '*' ],
            'order-list-page')->setPageName('orderPage');

        return view('pick.list', compact('ordersCollection', 'pageTitle'));
    }

    public function orderUpdate($orderId, $status, Request $request)
    {
        $userId = \Auth::user()->id;
        if ($order = PickOrder::find($orderId)) {
            $order->status = $status;

            if($status == PickOrder::ORDER_PICKED){
                $order->pick_user = $userId;
            }elseif($status == PickOrder::ORDER_SUBMIT){
                $order->submit_user = $userId;
            }

            if ($order->save()) {

                // 生成配送单
                if($status == PickOrder::ORDER_SUBMIT){
                    $pickOrder = new DeliveryOrder();
                    $pickOrder->order_no = 'JH' . date('YmdHis');
                    $pickOrder->status = PickOrder::ORDER_UNPICK;
                    $pickOrder->order_id = $order->order_id;
                    $pickOrder->save();
                }

                if ($status == 2) {
                    return responseSuccess('订单分拣成功');
                } else {
                    return responseSuccess('订单提交成功');
                }
            }

            return responseSuccess('订单状态更新失败');
        }

        return responseError('未找到订单信息');
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
                return responseSuccess('订单已成功接受');
            }

            return responseError('订单接受失败');
        }

        return responseError('未找到该订单');
    }

}