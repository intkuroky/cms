@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box page-filter-form">
            <form class="form-horizontal" novalidate="" method="get">
                <div class="box-header with-border">
                    <h4>配送员搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="username" class="col-sm-3 control-label padding-left-big">名称</label>

                                <div class="col-sm-9">
                                    <input id="username" type="text" class="form-control" placeholder="请输入配送员名称"
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
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">身份证号</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text"
                                           class="form-control ng-pristine ng-untouched ng-valid" placeholder="请输入配送员身份证号"
                                           name="id_cart_no" value="{{ isset($filters['id_cart_no'])?$filters['id_cart_no']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">性别</label>

                                <div class="col-sm-9">

                                    <?php $orderStatus = \App\Models\DeliveryMan::getDeliveryManSexMaps() ?>
                                    <select class="form-control" name="sex" id="sex">
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
                    <a href="{{ url('delivery/man') }}" class="btn btn-sm btn-default" target="_self">清除搜索</a>
                </div>

            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">配送员列表</h3>
                <a class="btn btn-sm btn-flat bg-aqua pull-right" onclick="showAddUserForm()">
                    <i class="fa fa-plus"></i>
                    添加配送员
                </a>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>配送员名称</th>
                        <th>状态</th>
                        <th>性别</th>
                        <th>手机号</th>
                        <th>身份证号</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($deliveryMans as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>

                            <td>
                                @if($user['status'] == \App\Models\DeliveryMan::STATUS_FREE)
                                    <span class="label label-default label-primary">{{ $user['deliveryStatus'] }}</span>
                                @elseif($user['status'] == \App\Models\DeliveryMan::STATUS_DELIVERING)
                                    <span class="label label-default label-primary">{{ $user['deliveryStatus'] }}</span>
                                @endif
                            </td>
                            <td>{{ $user['manSex'] }}</td>
                            <td>{{ $user['phone'] }}</td>
                            <td>{{ $user['id_card_no'] }}</td>
                            <td>
                                <a class="btn btn-flat btn-sm btn-default" href="{{ route('delivery.man-edit', $user['id']) }}"
                                   data-toggle="tooltip" data-placement="top"
                                   data-original-title="修改用户">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                <a class="btn btn-sm btn-flat btn-danger" data-toggle="tooltip"
                                   data-placement="top" title="" data-original-title="删除用户"
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
                    @for($i = 1; $i <= $deliveryMans->lastPage(); $i++)
                        @if($i==$deliveryMans->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $deliveryMans->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $deliveryMans->total() }}项，{{ $deliveryMans->lastPage() }}页
                </div>
            </div>
        </div>

        <!-- 添加新用户 Modal ./begin -->
        <div class="modal fade" id="addUserForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">添加配送员</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">名字</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="create-name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">性别</label>
                                <div class="col-sm-9">
                                    <select name="create-sex" class="form-control" id="">
                                        <option value="0">男</option>
                                        <option value="1">女</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">身份证号</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="create-id-card-no">
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
        <!-- 添加新用户 Modal ./end -->

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>
      $(function () {

        $('#sex').val('{{ isset($filters['sex'])? $filters['sex']:'' }}');
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
          sex: $('select[name="create-sex"]').val(),
          idCard: $('input[name="create-id-card-no"]').val()
        };

        $.post('/delivery/man/create', newRoleData, function (response) {
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