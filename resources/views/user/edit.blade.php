@extends('layouts.main')

@section('styles')
    <style>
        .customer-fields {
            display: none;
        }

        .customer-show {
            display: block;
        }

    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">编辑用户</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <form class="form-horizontal w5c-form" role="form" name="validateForm"
                                  onsubmit="return formSubmit()">

                                <div class="form-group col-md-6">
                                    <label class="col-sm-3 control-label">用户名</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="logName"
                                               value="{{ $user['name'] }}">
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-3 control-label">用户权限</label>
                                    <div class="col-sm-9">

                                        <?php
                                        $userPerms = [];
                                        if ( ! empty($user['perms'])) {
                                            foreach ($user['perms'] as $userPerm) {
                                                $userPerms[] = $userPerm['id'];
                                            }
                                        }
                                        ?>

                                        <select name="role" id="" class="form-control">
                                            @foreach($roles as $role)
                                                <option value="{{ $role['id'] }}" {{ $user->hasRole('$role["name"]') ? "selected":"" }}>{{ $role['display_name'] }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-3 control-label">姓名</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="displayName"
                                               value="{{ $user['display_name'] }}">
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-3 control-label">身份证号</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="idCard"
                                               value="{{ $user['id_card_no'] }}">
                                    </div>
                                </div>

                                <div class="col-md-12 customer-fields">
                                    <hr>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-3 control-label">联系电话</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="customer-phone"
                                               value="{{ $user['phone'] }}">
                                    </div>
                                </div>

                                    <?php $customerInfo = $user['customerInfo']; ?>
                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">许可证号</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="licence-number"
                                                   value="{{ $customerInfo['licence_no'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">发行单位</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="licence-company"
                                                   value="{{ $customerInfo['licence_company'] }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 customer-fields">
                                        <hr>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">企业名称</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="customer-cName"
                                                   value="{{ $customerInfo['company_name'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">企业类型</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="customer-cType"
                                                   value="{{ $customerInfo['company_type'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">邮编</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="customer-taxCode"
                                                   value="{{ $customerInfo['tax_code'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">供货单位</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="customer-sCompany"
                                                   value="{{ $customerInfo['supply_company'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">有效期限</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="customer-validTime"
                                                   data-inputmask="'alias': 'yyyy-mm-dd'" data-mask=""
                                                   value="{{ $customerInfo['invalid_time'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 customer-fields">
                                        <label class="col-sm-3 control-label">企业地址</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="customer-cAddress"
                                                   value="{{ $customerInfo['company_address'] }}">
                                        </div>
                                    </div>

                            </form>
                        </div>
                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <input type="button" class="btn btn-primary pull-right" onclick="formSubmit()" value="提交">
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>

      var customerRoleId = '{{ $customerRole->id }}', userType;
      $(function () {

        var userRoleSelector = $('select[name=role]');
        @if(!empty($userRole))
              userRoleSelector.val('{{ $userRole }}');
          @endif

          if (customerRoleId == '{{ $userRole }}') {
            userType = 'customer';
            $('.customer-fields').addClass('customer-show')
          }else{
            userType = 'manager';
          }

        userRoleSelector.on('change', function () {
          if ($(this).val() == customerRoleId) {
            $('.customer-fields').addClass('customer-show')
            userType = 'customer';
          } else {
            $('.customer-fields').removeClass('customer-show')
            userType = 'manager';
          }
        })

      })

      function formSubmit() {

        var permission = [], role = $('select[name="role"]').val();
        var permissionChecked = $('input[name="permission"]:checked');
        for (var i = 0; i < permissionChecked.length; i++) {
          permission.push(permissionChecked[i].value);
        }

        var userData = {
          logName: $('input[name="logName"]').val(),
          role: role,
          displayName: $('input[name="displayName"]').val(),
          idCrad: $('input[name="idCard"]').val(),
          type: userType,
          phone: $('input[name="customer-phone"]').val()
        };

        if (role == customerRoleId) {
            userData.licenceNo = $('input[name="licence-number"]').val(),
            userData.licenceCo = $('input[name="licence-company"]').val(),
            userData.cName = $('input[name="customer-cName"]').val(),
            userData.cType = $('input[name="customer-cType"]').val(),
            userData.taxCode = $('input[name="customer-taxCode"]').val(),
            userData.sCompany = $('input[name="customer-sCompany"]').val(),
            userData.validTime = $('input[name="customer-validTime"]').val(),
            userData.cAddress = $('input[name="customer-cAddress"]').val()
        }

        $.post('/user/update/{{ $user['id'] }}', userData, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.href = "/user/list";
            });
          } else {
            swal('', response.message, 'error');
          }
        });

        return false;
      }
    </script>
@endsection