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
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">申请状态</label>

                                <div class="col-sm-9">

                                    <?php $orderStatus = \App\Models\Order::getOrderStatusMaps() ?>
                                    <select class="form-control" name="apply_status" id="apply_status">
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
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">配送状态</label>

                                <div class="col-sm-9">

                                    <?php $deliveyStatus = \App\Models\Order::getDeliveryStatusMaps() ?>
                                    <select class="form-control" name="delivery_status" id="delivery_status">
                                        <option value=""></option>
                                        @foreach($deliveyStatus as $key => $item)
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
                    <a href="{{ url('order/list') }}" class="btn btn-sm btn-default" target="_self">清除搜索</a>
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
                        <th style="width: 10px">#</th>
                        <th>订单编号</th>

                        <th>下单人</th>
                        <th>申请状态</th>
                        <th>配送状态</th>
                        <th>拒绝原因</th>
                        <th>创建日期</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($ordersCollection as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $order['order_no'] }}</td>

                            <td>
                                <?php $user = \App\User::find($order['user_id'])?>
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
                            <td class="text-center">
                                @if($order['deliver_status'] == 1)
                                    <span class="label label-default label-primary">{{ $order['orderDeliveryStatus'] }}</span>
                                @elseif($order['deliver_status'] == 2)
                                    <span class="label-status label label-success">{{ $order['orderDeliveryStatus'] }}</span>
                                @elseif($order['deliver_status'] == 3)
                                    <span class="label label-info label-warning">{{ $order['orderDeliveryStatus'] }}</span>
                                @elseif($order['deliver_status'] == 4)
                                    <span class="label label-info label-warning">{{ $order['orderDeliveryStatus'] }}</span>
                                @else
                                    <span class="label label-info label-default">暂无</span>
                                @endif
                            </td>
                            <td>{{ $order['reject_reason'] }}</td>
                            <td>{{ $order['created_at'] }}</td>
                            <td>
                                <a href="{{ route('order.info', $order['id']) }}"
                                   class="btn btn-flat btn-sm btn-default" data-toggle="tooltip"
                                   data-placement="top"
                                   data-original-title="查看订单">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                <a class="btn btn-sm btn-flat btn-danger" data-toggle="tooltip"
                                   data-placement="top" title="" data-original-title="删除订单"
                                   onclick="deleteOrder('{{ $order['id'] }}')">
                                    <i class="fa fa-close"></i>
                                </a>

                                @if(\Auth::user()->hasRole('seller'))
                                    @if($order['status'] ==\App\Models\Order::ORDER_APPLYING)
                                        <a class="btn btn-flat btn-sm btn-success" data-toggle="tooltip"
                                           data-placement="top" onclick="adoptOrder({{ $order['id'] }})"
                                           data-original-title="接收订单">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>

                                        <a onclick="showRejectModal({{ $order['id'] }})"
                                           class="btn btn-flat btn-sm bg-red" data-toggle="tooltip"
                                           data-placement="top"
                                           data-original-title="拒绝订单">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    @endif
                                @endif
                                @if(\Auth::user()->hasRole('customer'))
                                    @if($order['deliver_status'] == \App\Models\Order::ORDER_COMPLETE)
                                        <a onclick="receivedOrder({{ $order['id'] }})"
                                           class="btn btn-flat btn-sm btn-success" data-toggle="tooltip"
                                           data-placement="top"
                                           data-original-title="确认收货">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif($order['deliver_status'] == \App\Models\Order::ORDER_RECEIVED)
                                        <a onclick="showCommentModal({{ $order['id'] }})"
                                           class="btn btn-flat btn-sm btn-success" data-toggle="tooltip"
                                           data-placement="top"
                                           data-original-title="去评价">
                                            <i class="fa fa-commenting-o"></i>
                                        </a>
                                    @endif
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
                                    <textarea name="reject-reason" rows="2" class="form-control"></textarea>
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


        <!-- 订单评价 Modal ./begin -->
        <div class="modal fade" id="commentForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">订单评价</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">评价内容</label>
                                <div class="col-sm-9">
                                    <textarea name="comment" rows="2" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
                            <input type="button" class="btn btn-primary" onclick="orderComment()" value="提交">
                            <input type="hidden" id="comment-order-id">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>

    <script>
      $(function () {

        $('#delivery_status').val('{{ isset($filters['delivery_status'])? $filters['delivery_status']:'' }}');
        $('#apply_status').val('{{ isset($filters['apply_status'])? $filters['apply_status']:'' }}');

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


      function showRejectModal(orderId) {
        $('#action-order-id').val(orderId);
        $('#refectForm').modal('show');
      }

      function showCommentModal(orderId) {
        $('#comment-order-id').val(orderId);
        $('#commentForm').modal('show');
      }

      function receivedOrder(orderId) {
        $.post('/order/received/' + orderId, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function orderComment() {
        var orderId = $('#comment-order-id').val();
        $.post('/order/comment/' + orderId, {comment: $('textarea[name=comment]').val()}, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function rejectOrder() {
        var orderId = $('#action-order-id').val();
        if (!orderId) return;
        $.post('/seller/reject-order/' + orderId, {reject_reason: $('textarea[name=reject-reason]').val()}, function (response) {
          if (response.errCode == 0) {
            $('#refectForm').modal('hide');
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function adoptOrder(orderId) {
        $.post('/seller/adopt-order/' + orderId, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function deleteOrder(orderId) {
          postRequest('/order/delete/' + orderId);
      }
    </script>
@endsection