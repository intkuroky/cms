@section('title', $pageTitle)

@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box page-filter-form">
            <form class="form-horizontal" novalidate="" method="get">
                <div class="box-header with-border">
                    <h4>订单搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="username" class="col-sm-3 control-label padding-left-big">订单号</label>

                                <div class="col-sm-9">
                                    <input id="username" type="text" class="form-control" placeholder="请输入订单号"
                                           name="order_id"
                                           value="{{ isset($filters['order_id'])?$filters['order_id']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">下单人</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入下单人姓名"
                                           name="name" value="{{ isset($filters['name'])?$filters['name']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">状态</label>

                                <div class="col-sm-9">

                                    <?php $orderStatus = \App\Models\DeliveryOrder::getDeliveryOrderStatusMaps() ?>
                                    <select class="form-control" name="status" id="status">
                                        <option value=""></option>
                                        @foreach($orderStatus as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="date-pick" class="col-sm-3 control-label padding-left-big">下单时间</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" id="date-pick" placeholder="点击选择日期区间"
                                               name="datetime" readonly=""
                                               value="{{ isset($filters['datetime'])?$filters['datetime']:'' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary" ng-click="searchBuyer()">搜索</button>
                    <a href="{{ url('delivery/order') }}" class="btn btn-sm btn-default" target="_self">清除搜索</a>
                </div>

            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">订单列表</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        {{--<th><input type="checkbox" onclick="checkAll(elem)"></th>--}}
                        <th style="width: 10px">#</th>
                        <th>订单编号</th>
                        <th>订单商品</th>
                        <th>下单人</th>
                        <th>订单状态</th>
                        <th>配送员</th>
                        <th>配送车辆</th>
                        <th>创建日期</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($ordersCollection as $key => $order)
                        <tr>
                            {{--<td>--}}
                            {{--<input type="checkbox" name="delivery-order" value="{{ $order['id'] }}">--}}
                            {{--</th>--}}
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $order['order_no'] }}</td>
                            <td>
                                <ul class="products-list product-list-in-box">
                                    @foreach($order['orderGoods'] as $orderGood)
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="{{ asset($orderGood['img']) }}"
                                                     alt="{{ $orderGood['goods_name'] }}">
                                            </div>
                                            <div class="product-info">
                                                <a href="{{ route('goods.info', $orderGood['goods_id']) }}"
                                                   class="product-title">
                                                    {{ $orderGood['goods_name'] }}
                                                </a>
                                                <span class="product-description">
                                                <span style="margin-right: 5px">￥{{ $orderGood['price'] }}</span> x <span
                                                            style="margin-left: 5px">{{ $orderGood['quantity'] }}</span>
                                            </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <?php
                                $orderModel = \App\Models\Order::find($order['order_id']);
                                $user = \App\User::find($orderModel->user_id);
                                ?>
                                {{ $user['display_name'] }}
                            </td>
                            <td class="text-center">
                                @if($order['status'] == 1)
                                    <span class="label label-default label-primary">{{ $order['orderStatus'] }}</span>
                                @elseif($order['status'] == 2)
                                    <span class="label-status label label-success">{{ $order['orderStatus'] }}</span>
                                @elseif($order['status'] == 3)
                                    <span class="label label-info label-warning">{{ $order['orderStatus'] }}</span>
                                @endif
                            </td>
                            <td>
                                <?php $man = \App\Models\DeliveryMan::find($order['delivery_user']) ?>
                                <span class="text-warning">
                                {{ $man['name'] }}
                                </span>
                            </td>
                            <td>
                                <?php $car = \App\Models\DeliveryCar::find($order['delivery_car']) ?>
                                <span class="text-success">
                                {{ $car['name'] }}
                                </span>
                            </td>
                            <td>{{ $order['created_at'] }}</td>
                            <td>
                                @if($order['status'] == \App\Models\DeliveryOrder::ORDER_DELIVERY_NONE)
                                    <a onclick="chooseDeliveryUser({{ $order['id'] }})"
                                       class="btn btn-flat btn-sm btn-default" data-toggle="tooltip"
                                       data-placement="top"
                                       data-original-title="开始配送">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                @elseif($order['status'] == \App\Models\DeliveryOrder::ORDER_DELIVERING)
                                    <a class="btn btn-flat btn-sm btn-success" data-toggle="tooltip"
                                       data-placement="top" onclick="deliveryComplete({{ $order['id'] }}, 3)"
                                       data-original-title="配送完成">
                                        <i class="fa  fa-paper-plane-o"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <li><a href="#">«</a></li>
                    @for($i = 1; $i <= $ordersCollection->lastPage(); $i++)
                        @if($i==$ordersCollection->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $ordersCollection->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $ordersCollection->total() }}项，{{ $ordersCollection->lastPage() }}页
                </div>
            </div>

        </div>

        <!-- 拒绝订单 Modal ./begin -->
        <div class="modal fade" id="chooseDeliveryOrderForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">选择配送人</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">配送员</label>
                                <div class="col-sm-9">
                                    <select name="" id="deliveryMan" class="form-control">
                                        @foreach($deliveryMans as $deliveryMan)
                                            <option value="{{ $deliveryMan['id'] }}">{{ $deliveryMan['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">配送车辆</label>
                                <div class="col-sm-9">
                                    <select name="" id="deliveryCar" class="form-control">
                                        @foreach($deliveryCars as $deliveryCar)
                                            <option value="{{ $deliveryCar['id'] }}">{{ $deliveryCar['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
                            <input type="button" class="btn btn-primary" onclick="deliveryStart()" value="提交">
                            <input type="hidden" id="action-order-id">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- 添加新用户 Modal ./end -->

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>

    <script>
      $(function () {

        $('#status').val('{{ isset($filters['status'])? $filters['status']:'' }}');

        $('#date-pick').daterangepicker({
          timePicker: false,
          format: 'YYYY-MM-DD HH:mm:ss',
          separator: ' - ',
          locale: {
            applyLabel: '确定',
            cancelLabel: '取消',
            fromLabel: '从',
            toLabel: '到',
            customRangeLabel: 'Custom',
            daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            firstDay: 0
          }
        });
      })

      var selectAll = false;
      function checkAll() {
        if (!selectAll) {


        }
      }

      function chooseDeliveryUser(orderId) {
        $('#action-order-id').val(orderId);
        $('#chooseDeliveryOrderForm').modal('show');
      }

      function changeStockOrderStatus(orderId, status) {
        $.post('/stock/order-update/' + orderId + '/' + status, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function deliveryComplete(orderId) {
        $.post('/delivery/complete/' + orderId, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function deliveryStart() {
        var orderId = $('#action-order-id').val();
        ;
        var deliveryManId = $('#deliveryMan').val();
        var deliveryCarId = $('#deliveryCar').val();
        var errorMsg = '';
        if (!deliveryCarId || !deliveryManId) {
          errorMsg = '请选择配送人和配送车辆';
          swal({
            title: '',
            text: errorMsg,
            type: 'warning'
          })
          return;
        }

        var data = {
          manId: deliveryManId,
          carId: deliveryCarId
        };

        $.post('/delivery/start/' + orderId, data, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }
    </script>
@endsection