@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">用户信息</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">

                        <form class="form-horizontal w5c-form" role="form" name="validateForm"
                              onsubmit="return formSubmit()">

                            <div class="form-group col-md-6">
                                <label class="col-sm-3 control-label">用户名</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $user['name'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="col-sm-3 control-label">用户权限</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $userRoleName }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="col-sm-3 control-label">姓名</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $user['display_name']?$user['display_name']: "无" }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="col-sm-3 control-label">身份证号</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $user['id_card_no']?$user['id_card_no']: "无" }}</span>
                                </div>
                            </div>

                            <div class="col-md-12 customer-fields">
                                <hr>
                            </div>

                            <?php $customerInfo = $user['customerInfo']; ?>
                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">联系电话</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $user['phone'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">许可证号</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['licence_no'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">发行单位</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['licence_company'] }}</span>
                                </div>
                            </div>

                            <div class="col-md-12 customer-fields">
                                <hr>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">企业名称</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['company_name'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">企业类型</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['company_type'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">邮编</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['tax_code'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">供货单位</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['supply_company'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">有效期限</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['invalid_time'] }}</span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 customer-fields">
                                <label class="col-sm-3 control-label">企业地址</label>
                                <div class="col-sm-9">
                                    <span class="form-control no-border">{{ $customerInfo['company_address'] }}</span>
                                </div>
                            </div>

                        </form>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>

      function formSubmit() {

        var permission = [];
        var permissionChecked = $('input[name="permission"]:checked');
        for (var i = 0; i < permissionChecked.length; i++) {
          permission.push(permissionChecked[i].value);
        }

        var roleData = {
          name: $('input[name="name"]').val(),
          role: $('select[name="role"]').val(),
          email: $('input[name="email"]').val()
        };

        $.post('/user/update/{{ $user['id'] }}', roleData, function (response) {
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
    </script>
@endsection