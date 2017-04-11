@section('title', $pageTitle)

@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box box-danger page-filter-form">
            <form class="form-horizontal ng-pristine ng-valid" novalidate="">
                <div class="box-header with-border">
                    <h4>订单搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="username" class="col-sm-3 control-label padding-left-big">订单名称</label>

                                <div class="col-sm-9">
                                    <input id="username" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入买家名称"
                                           name="username" ng-model="filterParameters.username">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">手机号码</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入手机号码"
                                           name="mobile" ng-model="filterParameters.mobile">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="date-pick" class="col-sm-3 control-label padding-left-big">加入时间</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control ng-pristine ng-untouched ng-valid"
                                               id="date-pick" placeholder="点击选择日期区间" ng-model="dateRange" readonly="">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer text-right">
                    <button type="button" class="btn btn-sm btn-primary" ng-click="searchBuyer()">搜索</button>
                    <a href="http://mp.dev.ysyhaitao.com/buyer" class="btn btn-sm btn-default" target="_self">清除搜索</a>
                </div>

            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $pageTitle }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>订单编号</th>
                        <th>订单商品</th>
                        <th>下单人</th>
                        <th>订单状态</th>
                        <th>分拣人</th>
                        <th>提交人</th>
                        <th>创建日期</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($ordersCollection as $key => $order)
                        <tr>
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
                                @if($user = \App\User::find($order['pick_user']))
                                    {{ $user['name'] }}
                                @endif
                            </td>
                            <td>
                                @if($user = \App\User::find($order['submit_user']))
                                    {{ $user['name'] }}
                                @endif
                            </td>
                            <td>{{ $order['created_at'] }}</td>
                            <td>
                                @if($order['status'] == \App\Models\PickOrder::ORDER_UNPICK)
                                <a onclick="changePickOrderStatus({{ $order['id'] }}, 2)"
                                   class="btn btn-flat btn-sm btn-default" data-toggle="tooltip"
                                   data-placement="top"
                                   data-original-title="分拣">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                                @elseif($order['status'] == \App\Models\PickOrder::ORDER_PICKED)
                                <a class="btn btn-flat btn-sm btn-success" data-toggle="tooltip"
                                   data-placement="top" onclick="changePickOrderStatus({{ $order['id'] }}, 3)"
                                   data-original-title="提交">
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
        <div class="modal fade" id="refectForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">拒绝订单</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">拒绝理由</label>
                                <div class="col-sm-9">
                                    <textarea name="reject-reason"  rows="2" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
                            <input type="button" class="btn btn-primary" onclick="rejectOrder()" value="提交">
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

        function changePickOrderStatus(orderId, status) {
          $.post('/pick/order-update/' + orderId + '/' + status, function (response) {
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