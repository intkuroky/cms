@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="box page-filter-form">
            <form class="form-horizontal" novalidate="" method="get">
                <div class="box-header with-border">
                    <h4>配送车俩搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="username" class="col-sm-3 control-label padding-left-big">司机名字</label>

                                <div class="col-sm-9">
                                    <input id="username" type="text" class="form-control" placeholder="请输入司机名字"
                                           name="name"
                                           value="{{ isset($filters['name'])?$filters['name']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">手机号</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入配送员手机号"
                                           name="phone" value="{{ isset($filters['phone'])?$filters['phone']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">车牌号</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入车辆车牌号"
                                           name="car_no" value="{{ isset($filters['car_no'])?$filters['car_no']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">运载体积</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入配送车辆运载体积"
                                           name="carry_volume" value="{{ isset($filters['carry_volume'])?$filters['carry_volume']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">状态</label>

                                <div class="col-sm-9">

                                    <?php $orderStatus = \App\Models\DeliveryMan::getDeliveryManStatusMaps() ?>
                                    <select class="form-control" name="status" id="status">
                                        <option value=""></option>
                                        @foreach($orderStatus as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary" ng-click="searchBuyer()">搜索</button>
                    <a href="{{ url('delivery/car') }}" class="btn btn-sm btn-default" target="_self">清除搜索</a>
                </div>

            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">配送车辆列表</h3>
                <a class="btn btn-sm btn-flat bg-aqua pull-right" onclick="showAddUserForm()">
                    <i class="fa fa-plus"></i>
                    添加配送车辆
                </a>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>车牌号</th>
                        <th>司机</th>
                        <th>运载体积</th>
                        <th>联系电话</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($deliveryCars as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['car_no'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['carry_volume'] }}</td>
                            <td>{{ $user['phone'] }}</td>
                            <td>
                                @if($user['status'] == \App\Models\DeliveryMan::STATUS_FREE)
                                    <span class="label label-default label-primary">{{ $user['deliveryStatus'] }}</span>
                                @elseif($user['status'] == \App\Models\DeliveryMan::STATUS_DELIVERING)
                                    <span class="label label-default label-primary">{{ $user['deliveryStatus'] }}</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-flat btn-sm btn-default" href="{{ route('delivery.car-edit', $user['id']) }}"
                                   data-toggle="tooltip" data-placement="top"
                                   data-original-title="修改车辆">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                <a class="btn btn-sm btn-flat btn-danger" data-toggle="tooltip"
                                   data-placement="top" title="" data-original-title="删除车辆"
                                   onclick="deleteDeliveryMan('{{ $user['id'] }}')">
                                    <i class="fa fa-close"></i>
                                </a>

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
                    @for($i = 1; $i <= $deliveryCars->lastPage(); $i++)
                        @if($i==$deliveryCars->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $deliveryCars->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $deliveryCars->total() }}项，{{ $deliveryCars->lastPage() }}页
                </div>
            </div>
        </div>

        <!-- 添加新车辆 Modal ./begin -->
        <div class="modal fade" id="addUserForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">添加车辆</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">司机</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="create-name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">车牌号</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="create-car-no">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">运载体积</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="create-carry_volume">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">联系电话</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="create-phone">
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
                            <input type="button" class="btn btn-primary" onclick="formSubmit()" value="提交">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- 添加新车辆 Modal ./end -->

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

      function showAddUserForm() {
        $('#addUserForm').modal('show');
      }

      function formSubmit() {

        var newRoleData = {
          name: $('input[name="create-name"]').val(),
          phone: $('input[name="create-phone"]').val(),
          carryVolume: $('input[name="create-carry_volume"]').val(),
          carNo: $('input[name="create-car-no"]').val(),
        };

        $.post('/delivery/car/create', newRoleData, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });

        return false;
      }

      function deleteDeliveryMan(id) {
        $.post('/delivery/man/delete/' + id, function (response) {
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