@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">角色列表</h3>
                <a class="btn btn-sm btn-flat bg-aqua pull-right" onclick="showAddRoleForm()">
                    <i class="fa fa-plus"></i>
                    添加角色
                </a>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>角色名称</th>
                        <th>角色</th>
                        <th>描述</th>
                        <th>操作</th>
                    </tr>
                    @foreach ($rolesCollection as $role)
                        <tr>
                            <td>{{ $role['id'] }}</td>
                            <td>{{ $role['name'] }}</td>
                            <?php
                            $rolePerms = [];
                            foreach ($role['perms'] as $rolePerm) {
                                $rolePerms[] = $rolePerm['name'];
                            }
                            ?>
                            <td>{{ implode($rolePerms, '，') }}</td>
                            <td>{{ $role['description'] }}</td>
                            <td>
                                <a class="btn btn-flat btn-sm btn-default" href="{{ route('roles.edit', $role['id']) }}"
                                   data-toggle="tooltip" data-placement="top"
                                   data-original-title="修改角色">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                <a class="btn btn-sm btn-flat btn-danger" data-toggle="tooltip"
                                   data-placement="top" title="" data-original-title="删除角色"
                                   onclick="deleteRole('{{ $role['id'] }}')">
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
                    @for($i = 1; $i <= $rolesCollection->lastPage(); $i++)
                        @if($i==$rolesCollection->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $rolesCollection->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $rolesCollection->total() }}项，{{ $rolesCollection->lastPage() }}页
                </div>
            </div>
        </div>

        <!-- 添加新角色 Modal ./begin -->
        <div class="modal fade" id="addPermissionForm">
            <div class="modal-dialog">
                <form class="form-horizontal w5c-form" role="form" name="validateForm" onsubmit="return formSubmit()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">添加角色</h4>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">角色</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">展示名称</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="display-name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">角色权限</label>
                                <div class="col-sm-9">

                                    <?php
                                    $rolePerms = [];
                                    if ( ! empty($role['perms'])) {
                                        foreach ($role['perms'] as $rolePerm) {
                                            $rolePerms[] = $rolePerm['id'];
                                        }
                                    }
                                    ?>
                                    @foreach($permissions as $permission)

                                        <div class="checkbox col-md-4">
                                            <label>
                                                @if(in_array($permission['id'], $rolePerms))
                                                    <input type="checkbox" name="permission"
                                                           value="{{ $permission['id'] }}"
                                                           checked="checked">{{ $permission['display_name'] }}
                                                @else
                                                    <input type="checkbox" name="permission"
                                                           value="{{ $permission['id'] }}">{{ $permission['display_name'] }}
                                                @endif
                                            </label>
                                        </div>

                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">角色描述</label>
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
        <!-- 添加新角色 Modal ./end -->

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>

      function showAddRoleForm() {
        $('#addPermissionForm').modal('show');
      }

      function formSubmit() {

        var newRoleData = {
          name: $('input[name="name"]').val(),
          description: $('textarea[name="description"]').val(),
          displayName: $('input[name="display-name"]').val()
        };

        $.post('/roles/add', newRoleData, function (response) {
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
        $.post('/roles/delete/' + roleId, function (response) {
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