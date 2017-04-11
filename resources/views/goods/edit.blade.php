@extends('layouts.main')
@section('styles')
    <style>
        .bar {
            height: 5px;
            background: green;
        }

        #progress {
            width: 130px;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-8">

                <div class="box ">
                    <div class="box-header with-border">
                        <h3 class="box-title">商品信息</h3>
                    </div>
                    <form class="form-horizontal" role="form" name="validateForm" method="post"
                          enctype="multipart/form-data">
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">商品名称：</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name"
                                           value="{{ $goods['name'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">商品价格：</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="price"
                                           value="{{ $goods['price'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">商品库存：</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="store-num"
                                           value="{{ $goods['store_num'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">商品条形码：</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="bar-code"
                                           value="{{ $goods['bar_code'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">生产厂家：</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="supply-company"
                                           value="{{ $goods['supply_company'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-2 text-right">商品图片：</label>
                                <div class="col-md-9">

                                    <img class="img-responsive shop-entry-logo" style="height: 110px;width: 130px"
                                         name="goods-img" src="{{ asset($goods['img']?:'/imgs/thumb.png') }}">
                                    <input type="hidden" name="goods-img" value="{{ $goods['img']?:'/imgs/thumb.png' }}">
                                    <span class="btn btn-success fileinput-button" style="margin-top: 20px">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>选择商品图片<i id="upload-process"></i></span>
                                        <input type="file" id="imageUpload" data-url="{{ route('file.upload') }}"
                                               name="goodsImage">
                                    </span>

                                </div>
                            </div>


                            <br>
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-right">商品描述：</label>
                                <div class="col-sm-9">
                                    <textarea name="description" id="" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <br>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <a class="btn btn-primary pull-right" onclick="formSubmit()">保存</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>

      function formSubmit() {
        var newGoodsData = {
          name: $('input[name=name]').val(),
          price: $('input[name=price]').val(),
          storeNum: $('input[name=store-num]').val(),
          barCode: $('input[name=bar-code]').val(),
          supplyCompany: $('input[name=supply-company]').val(),
          goodsImg: $('input[name=goods-img]').val(),
          description: $('textarea[name=description]').val()
        };

        postRequest('{{ route('goods.update', $goods['id']) }}', newGoodsData, '{{ route('goods.list') }}');
      }

      $(function () {
        $('#imageUpload').fileupload({
          dataType: 'json',
          done: function (e, data) {
            if (data && data.result && data.result.errCode == 0) {
              $('img[name=goods-img]').attr('src', data.result.data['goodsImage']);
              $('input[name=goods-img]').val(data.result.data['goodsImage']);
            } else {
              swal('', '文件上传失败', 'warning');
            }
          },
          progressall: function (e, data) {
            $('#upload-process').text();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#upload-process').text('(' + progress + '%)')
            $('#progress .bar').css(
              'width',
              progress + '%'
            );
          }
        });

      })
    </script>
@endsection