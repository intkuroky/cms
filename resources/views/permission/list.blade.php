@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">权限列表</h3>
                <a class="btn btn-sm btn-flat bg-aqua pull-right" onclick="showAddPermissionForm()">
                    <i class="fa fa-plus"></i>
                    添加权限
                </a>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>权限名称</th>
                        <th>描述</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($permissionCollection as $permission)
                        <tr>
                            <td>{{ $permission['id'] }}</td>
                            <td>{{ $permission['name'] }}</td>
                            <td>{{ $permission['description'] }}</td>
                            <td>
                                <a class="btn btn-flat btn-sm btn-default" href="{{ route('permission.edit', $permission['id']) }}"
                                   data-toggle="tooltip" data-placement="top"
                                   data-original-title="修改权限">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                <a class="btn btn-sm btn-flat btn-danger" data-toggle="tooltip"
                                   data-placement="top" title="" data-original-title="删除权限"
                                   onclick="deleteRole('{{ $permission['id'] }}')">
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
                    @for($i = 1; $i <= $permissionCollection->lastPage(); $i++)
                        @if($i==$permissionCollection->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $permissionCollection->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $permissionCollection->total() }}项，{{ $permissionCollection->lastPage() }}页
                </div>
            </div>
        </div>

        <!-- 添加新权限 Modal ./begin -->
        <div class="modal fade" id="addPermissionForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">添加权限</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">权限名称</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">显示名称</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="display-name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">权限描述</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="description" id="" cols="30"
                                              rows="2"></textarea>
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
        <!-- 添加新权限 Modal ./end -->

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>

      function showAddPermissionForm() {
        $('#addPermissionForm').modal('show');
      }

      function formSubmit() {

        var permission = [];
        var permissionChecked = $('input[name="permission"]:checked');
        for (var i = 0; i < permissionChecked.length; i++) {
          permission.push(permissionChecked[i].value);
        }

        var newRoleData = {
          name: $('input[name="name"]').val(),
          displayName: $('input[name="display-name"]').val(),
          permission: permission,
          description: $('textarea[name="description"]').val()
        };

        $.post('/permission/add', newRoleData, function (response) {
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

      function deleteRole(roleId) {
        $.post('/permission/delete/' + roleId, function (response) {
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