@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box page-filter-form">
            <form class="form-horizontal" action="" method="get">
                <div class="box-header with-border">
                    <h4>订单搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label padding-left-big">用户</label>

                                <div class="col-sm-9">
                                    <input id="name" type="text" class="form-control" placeholder="请输入用户姓名或用户名"
                                           name="name" value="{{ isset($filters['name'])?$filters['name']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="role" class="col-sm-3 control-label padding-left-big">IP</label>

                                <div class="col-sm-9">
                                    <input id="name" type="text" class="form-control" placeholder="请输入查询IP"
                                           name="ip" value="{{ isset($filters['ip'])?$filters['ip']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="date-pick" class="col-sm-3 control-label padding-left-big">时间</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="datetime" class="form-control" id="date-pick"
                                               placeholder="点击选择日期区间" readonly=""  value="{{ isset($filters['datetime'])?$filters['datetime']:'' }}">
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary" ng-click="searchBuyer()">搜索</button>
                    <a href="{{ url('system-log') }}" class="btn btn-sm btn-default">清除搜索</a>
                </div>

            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">系统日志</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>标题</th>
                        <th>用户名</th>
                        <th>ip</th>
                        <th>类型</th>
                        <th>描述</th>
                        <th>时间</th>
                    </tr>

                    @foreach ($logsCollections as $log)
                        <tr>
                            <td>{{ $log['id'] }}</td>
                            <td>{{ $log['title'] }}</td>
                            <td>{{ $log['userDisplayName'] }}</td>
                            <td>{{ $log['ip'] }}</td>
                            @if($log['type'] == 1)
                                <td class="text-center">
                                    <span class="label label-success label-status">{{ $log['logType'] }}</span>
                                </td>
                            @elseif($log['type'] == 2)
                                <td class="text-center">
                                    <span class="label-status label label-warning" >{{ $log['logType'] }}</span>
                                </td>
                            @elseif($log['type'] == 3)
                                <td class="text-center">
                                    <span class="label label-info label-status">{{ $log['logType'] }}</span>
                                </td>
                            @elseif($log['type'] == 4)
                                <td class="text-center">
                                    <span class="label-status label label-info" >
                                        {{ $log['logType'] }}
                                    </span>
                                </td>
                            @endif
                            <td>{{ $log['content'] }}</td>
                            <td>{{ $log['created_at'] }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <li><a href="#">«</a></li>
                    @for($i = 1; $i <= $logsCollections->lastPage(); $i++)
                        @if($i==$logsCollections->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $logsCollections->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $logsCollections->total() }}项，{{ $logsCollections->lastPage() }}页
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>
      $(function () {

        $('#date-pick').daterangepicker({
          timePicker: false,
          format : 'YYYY-MM-DD HH:mm:ss',
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
          name: $('input[name="name"]').val(),
          role: $('select[name="role"]').val(),
          email: $('input[name="email"]').val()
        };

        $.post('/user/add', newRoleData, function (response) {
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

      function deleteUser(roleId) {
        $.post('/user/delete/' + roleId, function (response) {
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