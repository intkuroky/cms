@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="row">
            <div class="box col-md-12">
                <div class="box-header with-border">
                    <h3 class="box-title">商品信息
                        @if($goodsCollection['store_num'] <= 0)
                            <small class="text-danger">（商品已售空）</small>
                        @endif
                    </h3>
                </div>

                <!-- /.box-header -->
                <div class="box-body">

                    <div class="col-md-4">
                        <img style="width: 100%" src="{{ asset($goodsCollection['img']) }}"
                             alt="{{ $goodsCollection['name'] }}">
                    </div>
                    <div class="col-md-7">
                        <form role="form" name="validateForm" onsubmit="return formSubmit()">
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding">商品名称：</label>
                                <input type="text" class="form-control no-border" name="name"
                                       value="{{ $goodsCollection['name'] }}">
                            </div>
                        </form>
                        <form class="form-horizontal" action="">
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label no-padding"
                                       style="padding-left: 0px;text-align: left;">
                                    商品价格：￥{{ $goodsCollection['price'] }}
                                </label>
                                <div class="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label no-padding"
                                       style="padding-left: 0px;text-align: left;">
                                    当前库存：{{ $goodsCollection['store_num'] }} 件
                                </label>
                                <div class="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="col-sm-10 control-label no-padding"
                                       style="padding-left: 0px;text-align: left;">
                                    条形编码：{{ $goodsCollection['bar_code'] }}
                                </label>
                                <div class="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label no-padding"
                                       style="padding-left: 0px;text-align: left;">
                                    　
                                </label>
                                <div class="">
                                </div>
                            </div>
                            　
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-sm-2 control-label no-padding"
                                       style="padding-left: 0px;text-align: left;">
                                    生产厂商：
                                </label>
                                <div class="col-md-10 text-left">
                                    {{ $goodsCollection['supply_company'] }}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="col-sm-2 control-label no-padding"
                                       style="padding-left: 0px;text-align: left;">
                                    商品描述：
                                </label>
                                <div class="col-md-10 text-left">
                                    {{ $goodsCollection['description'] }}
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                {{--<a class="btn btn-primary pull-right" data-goodsId="{{ $goodsCollection['id'] }}" onclick="submitOrder(this)">立即购买</a>--}}
                @if(\Auth::user()->hasRole('customer'))
                    @if($goodsCollection['store_num'] > 0)
                        <input type="button" class="btn btn-success pull-right margin-r-5"
                               onclick="joinCart('{{ $goodsCollection['id'] }}', '{{ $goodsCollection['name'] }}', '{{ $goodsCollection['price'] }}')"
                               value="加入购物车">
                    @else
                        <input type="button" class="btn btn-success pull-right margin-r-5 disabled"
                               value="加入购物车">
                    @endif
                @endif
                <input type="hidden" name="goods-id" value="{{ $goodsCollection->id }}">
                <input type="hidden" name="quantity-{{ $goodsCollection->id }}" value="1">
                <input type="hidden" name="order-{{ $goodsCollection->id }}-total"
                       value="{{ $goodsCollection->price }}">
            </div>
        </div>
    </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>
      function joinCart(id, name, price) {
        var quantity = 1;
        var data = {
          id: id,
          qty: 1,
          goodsName: name,
          price: price
        }
        $.post('/cart/join-cart', data, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
//              window.location.reload();
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }
      function formSubmit() {

        var roleData = {
          name: $('input[name="name"]').val(),
          email: $('input[name="email"]').val()
        };

        $.post('/goods/update/{{ $goodsCollection['id'] }}', roleData, function (response) {
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

      function submitOrder(elem) {

        var goodsId = $(elem).data('goodsid');
        var orderData = [{
          goodsId: goodsId,
          qty: $('input[name=quantity-' + goodsId + ']').val()
        }];
        var totalPrice = $('input[name=order-' + goodsId + '-total]').val();
        $.post('/order/create', {data: orderData, total: totalPrice}, function (response) {
          if (response.errCode == 0) {
            responseSuccess(response.message, function () {
              window.location.href = "/order/list";
            });
          } else {
            swal('', response.message, 'error');
          }
        });
      }
    </script>
@endsection