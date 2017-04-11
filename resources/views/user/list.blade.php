@extends('layouts.main')

@section('styles')
    <style>
        .sub-nav {
            list-style: outside none none;
            margin: 0;
            padding: 0;
        }

        .sub-nav li {
            border-radius: 60px;
            background-color: #f2f2f2;
            color: #747474;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            /*margin-right: 8px;*/
            padding: 0;
            /*width: 150px;*/
            height: 40px;
            line-height: 40px;
            text-align: center;
            transition: all .3s ease 0s;
        }

        .sub-nav li a {
            display: block;
            width: 100%;
            height: 100%;
        }

        .sub-nav .active {
            background: #4169E1 none repeat scroll 0 0;
            color: #fff;
        }

        .sub-nav .active a {
            color: #fff !important;
        }

        .nav-content {
            padding-bottom: 20px;
            margin-bottom: 39px;
            border-bottom: 1px solid #f4f4f4;
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="box page-filter-form">
            <form class="form-horizontal" action="" method="get">
                <div class="box-header with-border">
                    <h4>搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label padding-left-big">用户</label>

                                <div class="col-sm-9">
                                    <input id="name" type="text" class="form-control" placeholder="请输入用户姓名或用户名"
                                           name="search-name" value="{{ isset($filters['search-name'])?$filters['search-name']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="role" class="col-sm-3 control-label padding-left-big">角色</label>

                                <div class="col-sm-9">
                                    <select name="search-role" id="role" class="form-control">
                                        <?php $roles = getRoleList(); ?>
                                            <option value=""></option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
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
                                        <input type="text" name="search-datetime" class="form-control" id="date-pick"
                                               placeholder="点击选择日期区间" readonly=""  value="{{ isset($filters['search-datetime'])?$filters['search-datetime']:'' }}">
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary" ng-click="searchBuyer()">搜索</button>
                    <a href="{{ url('user/list') }}" class="btn btn-sm btn-default">清除搜索</a>
                </div>

            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">用户列表</h3>
                <a class="btn btn-sm btn-flat bg-aqua pull-right" onclick="showAddUserForm()">
                    <i class="fa fa-plus"></i>
                    添加用户
                </a>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>用户名</th>
                        <th>姓名</th>
                        <th>角色</th>
                        <th>身份证号</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($userCollection as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['display_name'] }}</td>
                            <?php
                            $userRoles = [];
                            foreach ($user['roles'] as $userRole) {
                                $userRoles[] = $userRole['name'];
                            }
                            ?>
                            <td>{{ implode($userRoles, '，') }}</td>

                            <td>{{ $user['id_card_no'] }}</td>
                            <td>{{ $user['created_at'] }}</td>
                            <td>
                                <a class="btn btn-flat btn-sm btn-default" href="{{ route('user.edit', $user['id']) }}"
                                   data-toggle="tooltip" data-placement="top"
                                   data-original-title="修改用户">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                <a class="btn btn-sm btn-flat btn-danger" data-toggle="tooltip"
                                   data-placement="top" title="" data-original-title="删除用户"
                                   onclick="deleteUser('{{ $user['id'] }}')">
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
                    @for($i = 1; $i <= $userCollection->lastPage(); $i++)
                        @if($i==$userCollection->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $userCollection->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $userCollection->total() }}项，{{ $userCollection->lastPage() }}页
                </div>
            </div>
        </div>

        <!-- 添加新用户 Modal ./begin -->
        <div class="modal fade" id="addUserForm">
            <div class="modal-dialog modal-lg">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">添加用户</h4>
                        </div>

                        <div class="modal-body">
                            <div class="blog-column-menu">
                                <ul class="sub-nav clearfix nav-content" id="myTab">
                                    <li class="filter active first_li col-md-4 col-md-offset-1" data-type="customer">
                                        <a href="#personal" data-toggle="tab">客户</a>
                                    </li>
                                    <li class="filter col-md-4 col-md-offset-2" data-type="company">
                                        <a href="#company" data-toggle="tab">企业</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content content-btm">
                                    <div class="tab-pane fade in active tab-content row" id="personal">
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">用户名</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-logName">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">密码</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-password">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">角色</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly class="form-control" name="customer-role"
                                                       value="客户">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">姓名</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-displayName">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">身份证号</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-idCard">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">联系电话</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-phone">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">许可证号</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="licence-number">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">发行单位</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="licence-company">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <hr>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">企业名称</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-cName">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">企业类型</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-cType">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">邮编</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-taxCode">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">供货单位</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-sCompany">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">有效期限</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-validTime"
                                                       data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">企业地址</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="customer-cAddress">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade tab-content row" id="company">
                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">用户名</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="manager-logName">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">姓名</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="manager-displayName">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">身份证号码</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="manager-idCard">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">用户密码</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="manager-password">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">手机号</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="manager-phone">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-sm-3 control-label">用户角色</label>
                                            <div class="col-sm-9">
                                                <select name="manager-role" id="" class="form-control">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
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

        $('#role').val('{{ isset($filters['search-role'])? $filters['search-role']:'' }}');

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
        var type = $('#addUserForm .active').data('type');

        type == 'customer' ? addCustomer() : addCompany();
        return false;
      }

      function addCustomer() {
        var newCustomerData = {
          type: 'customer',
          logName: $('input[name="customer-logName"]').val(),
          password: $('input[name="customer-password"]').val(),
          role: 8,
          displayName: $('input[name="customer-displayName"]').val(),
          idCrad: $('input[name="customer-idCard"]').val(),
          phone: $('input[name="customer-phone"]').val(),
          licenceNo: $('input[name="licence-number"]').val(),
          licenceCo: $('input[name="licence-company"]').val(),
          cName: $('input[name="customer-cName"]').val(),
          cType: $('input[name="customer-cType"]').val(),
          taxCode: $('input[name="customer-taxCode"]').val(),
          sCompany: $('input[name="customer-sCompany"]').val(),
          validTime: $('input[name="customer-validTime"]').val(),
          cAddress: $('input[name="customer-cAddress"]').val()
        };

        postRequest('/user/add', newCustomerData);
      }

      function addCompany() {
        var newCompanyData = {
          type: 'manager',
          logName: $('input[name="manager-logName"]').val(),
          phone: $('input[name="manager-phone"]').val(),
          password: $('input[name="manager-password"]').val(),
          role: $('select[name="manager-role"]').val(),
          displayName: $('input[name="manager-displayName"]').val(),
          idCrad: $('input[name="manager-idCard"]').val(),
        };

        postRequest('/manager/add', newCompanyData);
      }

      function deleteUser(userId) {
        postRequest('/user/delete/' + userId);
      }
    </script>
@endsection