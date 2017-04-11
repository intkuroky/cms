<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\DeliveryCarListFilter;
use App\Http\Filters\DeliveryManListFilter;
use App\Http\Filters\DeliveryOrderListFilter;
use App\Http\Requests\AddDelivieryCarRequest;
use App\Http\Requests\AddDelivieryManRequest;
use App\Models\DeliveryCar;
use App\Models\DeliveryMan;
use App\Models\DeliveryOrder;
use App\Models\Order;
use App\Models\Permission;
use App\Models\PickOrder;
use App\Models\StockOrder;
use App\User;
use Illuminate\Http\Request;
use App\Models\Role;

class DeliveryController extends Controller
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

    public function deliveryOrder(DeliveryOrderListFilter $filter, Request $request)
    {
        $filters = $request->all();

        $pageTitle = '配送单管理';

        $deliveryMans = DeliveryMan::where('status', DeliveryMan::STATUS_FREE)->get();
        $deliveryCars = DeliveryCar::where('status', DeliveryCar::STATUS_FREE)->get();

        $ordersCollectionBuilder = DeliveryOrder::with('orderGoods');
        $ordersCollectionBuilder = $filter->filter($ordersCollectionBuilder, $filters);
        $ordersCollection        = $ordersCollectionBuilder->paginate(10, [ '*' ],
            'order-list-page')->setPageName('orderPage');

        return view('delivery.list',
            compact('ordersCollection', 'pageTitle', 'deliveryCars', 'deliveryMans', 'filters'));
    }

    public function deliveryMan(DeliveryManListFilter $filter, Request $request)
    {

        $filters = $request->all();

        $pageTitle           = '配送员管理';
        $deliveryMansBuilder = DeliveryMan::query();
        $deliveryMansBuilder = $filter->filter($deliveryMansBuilder, $filters);
        $deliveryMans        = $deliveryMansBuilder->paginate(10, [ '*' ], 'orderPage')->setPageName('orderPage');

        return view('delivery.man', compact('deliveryMans', 'pageTitle', 'filters'));
    }

    public function deliveryCar(DeliveryCarListFilter $filter, Request $request)
    {
        $pageTitle = '配送车辆管理';
        $filters   = $request->all();

        $deliveryCarsBuilder = DeliveryCar::query();
        $deliveryCarsBuilder = $filter->filter($deliveryCarsBuilder, $filters);
        $deliveryCars        = $deliveryCarsBuilder->paginate(10, [ '*' ], 'orderPage')->setPageName('orderPage');

        return view('delivery.car', compact('deliveryCars', 'pageTitle', 'filters'));
    }

    public function deliveryManEdit($manId)
    {
        if ( ! $man = DeliveryMan::find($manId)) {
            return responseError('未找到配送员信息');
        }

        $pageTitle = '配送员信息修改';

        return view('delivery.man-edit', compact('pageTitle', 'man'));
    }

    public function deliveryCarCreate(AddDelivieryCarRequest $request)
    {
        $name     = $request->get('name');
        $manExist = DeliveryMan::where('name', $name)->get();
        if (count($manExist) > 0) {
            return responseError('该名称已被占用');
        }
        $deliveryCarModel               = new DeliveryCar();
        $deliveryCarModel->name         = $name;
        $deliveryCarModel->status       = DeliveryCar::STATUS_FREE;
        $deliveryCarModel->car_no       = $request->get('carNo');
        $deliveryCarModel->phone        = $request->get('phone');
        $deliveryCarModel->carry_volume = $request->get('carryVolume');
        if ($deliveryCarModel->save()) {
            return responseSuccess('配送车辆添加成功');
        }

        return responseError('配送车辆添加失败');
    }

    public function deliveryManCreate(AddDelivieryManRequest $request)
    {

        $name     = $request->get('name');
        $manExist = DeliveryMan::where('name', $name)->get();
        if (count($manExist) > 0) {
            return responseError('该名称已被占用');
        }
        $deliveryManModel             = new DeliveryMan();
        $deliveryManModel->name       = $name;
        $deliveryManModel->status     = DeliveryMan::STATUS_FREE;
        $deliveryManModel->sex        = $request->get('sex');
        $deliveryManModel->id_card_no = $request->get('idCard');
        $deliveryManModel->phone      = $request->get('phone');
        if ($deliveryManModel->save()) {
            return responseSuccess('配送员添加成功');
        }

        return responseError('配送员添加失败');
    }

    public function deliveryManUpdate($manId, AddDelivieryManRequest $request)
    {
        if ( ! $man = DeliveryMan::find($manId)) {
            return responseError('未找到配送员信息');
        }

        $man->name       = $request->get('name');
        $man->status     = $request->get('status');
        $man->phone      = $request->get('phone');
        $man->id_card_no = $request->get('idCard');
        $man->sex        = $request->get('sex');

        if ($man->save()) {
            return responseSuccess('信息修改成功');
        }

        return responseError('信息修改失败');
    }

    public function deliveryCarUpdate($carId, AddDelivieryCarRequest $request)
    {
        if ( ! $car = DeliveryCar::find($carId)) {
            return responseError('未找到车辆信息');
        }

        $car->name         = $request->get('name');
        $car->status       = $request->get('status');
        $car->phone        = $request->get('phone');
        $car->carry_volume = $request->get('carryVolume');
        $car->car_no       = $request->get('carNo');

        if ($car->save()) {
            return responseSuccess('信息修改成功');
        }

        return responseError('信息修改失败');
    }

    public function deliveryCarDelete($carId, Request $request)
    {
        if ($order = DeliveryCar::where('delivery_user', $carId)->first()) {
            return responseError('配送车辆正在配送，不能删除');
        }
        if ( ! $car = DeliveryCar::find($carId)) {
            return responseError('未找到车辆信息');
        }
        DeliveryCar::destroy($carId);

        return responseSuccess('删除成功');
    }

    public function deliveryManDelete($manId, Request $request)
    {
        if ($order = DeliveryOrder::where('delivery_user', $manId)->first()) {
            return responseError('配送员正在配送，不能删除');
        }

        if ( ! $car = DeliveryMan::find($manId)) {
            return responseError('未找到配送员信息');
        }
        DeliveryMan::destroy($manId);

        return responseSuccess('删除成功');
    }

    public function deliveryCarEdit($carId)
    {
        if ( ! $car = DeliveryCar::find($carId)) {
            return responseError('未找到车辆信息');
        }

        $pageTitle = '配送车辆信息修改';

        return view('delivery.car-edit', compact('pageTitle', 'car'));
    }

    public function deliveryStart($orderId, Request $request)
    {

        $deliveryManId = $request->get('manId');
        $deliveryCarId = $request->get('carId');

        if ($order = DeliveryOrder::find($orderId)) {
            $order->status        = DeliveryOrder::ORDER_DELIVERING;
            $order->delivery_user = $deliveryManId;
            $order->delivery_car  = $deliveryCarId;

            if ($order->save()) {
                DeliveryMan::where('id', $deliveryManId)->update([ 'status' => DeliveryMan::STATUS_DELIVERING ]);
                DeliveryCar::where('id', $deliveryManId)->update([ 'status' => DeliveryCar::STATUS_DELIVERING ]);

                return responseSuccess('订单已经开始配送');
            }

            return responseError('订单配送失败！');
        }

        return responseError('未找到订单信息');
    }

    public function deliveryComplete($orderId, Request $request)
    {

        if ($order = DeliveryOrder::find($orderId)) {
            $order->status = DeliveryOrder::ORDER_DELIVERED;

            if ($order->save()) {
                Order::where('id', $order->order_id)->update([ 'deliver_status' => Order::ORDER_COMPLETE ]);
                DeliveryMan::where('id', $order->delivery_user)->update([ 'status' => DeliveryMan::STATUS_FREE ]);
                DeliveryCar::where('id', $order->delivery_car)->update([ 'status' => DeliveryCar::STATUS_FREE ]);

                return responseSuccess('订单配送完成');
            }

            return responseError('订单配送失败！');
        }

        return responseError('未找到订单信息');
    }

}