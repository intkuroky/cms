@extends('layouts.main')
@section('content')
    <div class="content col-md-8">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">个人信息</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户名称</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="{{ $user['name'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户权限</label>
                        <?php
                        $userRoles = [];
                        if ( ! empty($userRolesCollection = $user->roles)) {
                            foreach ($userRolesCollection as $userRole) {
                                $userRoles[] = $userRole['name'];
                            }
                        }
                        ?>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" name="role" value="{{ implode('，',$userRoles) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户邮箱</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="email" value="{{ $user['email'] }}">
                        </div>
                    </div>

                </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <button type="button" class="btn btn-default pull-left">返回</button>
                <input type="button" class="btn btn-primary pull-right" onclick="formSubmit()" value="提交">
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>

      function formSubmit() {

        var roleData = {
          name: $('input[name="name"]').val(),
          email: $('input[name="email"]').val()
        };

        $.post('/customer/update/{{ $user['id'] }}', roleData, function (response) {
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