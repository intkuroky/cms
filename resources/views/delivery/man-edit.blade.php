@extends('layouts.main')
@section('content')
    <div class="content col-md-8">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">编辑用户</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名字</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="{{ $man['name'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">配送状态</label>
                        <div class="col-sm-9">
                            <select name="status" class="form-control" id="">
                                <option value="1">空闲</option>
                                <option value="2">配送中</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">性别</label>
                        <div class="col-sm-9">
                            <select name="sex" class="form-control" id="">
                                <option value="0">男</option>
                                <option value="1">女</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">身份证号</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="id-card-no" value="{{ $man['id_card_no'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">联系电话</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="phone" value="{{ $man['phone'] }}">
                        </div>
                    </div>

                </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <input type="button" class="btn btn-primary pull-right" onclick="formSubmit()" value="提交">
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>
      $(function () {
        $('select[name=status]').val({{ $man['status'] }});
        $('select[name=sex]').val({{ $man['sex'] }});
      })
      function formSubmit() {
        var updateData = {
          name: $('input[name="name"]').val(),
          phone: $('input[name="phone"]').val(),
          sex: $('select[name="sex"]').val(),
          idCard: $('input[name="id-card-no"]').val(),
          status:$('select[name="status"]').val()
        };
        $.post('/delivery/man/update/{{ $man['id'] }}', updateData, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.href='/delivery/man';
            });
          } else {
            swal('', response.message, 'error');
          }
        });

        return false;
      }
    </script>
@endsection