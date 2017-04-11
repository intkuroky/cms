@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">购物车</h3>
                <button class="btn btn-danger pull-right" onclick="clearCart()">清空购物车</button>
            </div>
            <!-- /.box-header -->

            <form action="order/confirm-order" method="post" id="car-list-form">
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>商品名称</th>
                            <th>条形码</th>
                            <th>生产厂商</th>
                            <th>商品描述</th>
                            <th>操作</th>
                        </tr>
                        <?php $index = 1; ?>
                        @foreach ($cartCollections->content() as $key => $goods)
                            <tr>
                                <td>{{ $index }}</td>
                                <?php $index++; ?>
                                <td>
                                    <?php $goodsModel = \App\Models\Goods::find($goods->id)?>
                                    {{--@if($goodsModel)--}}
                                    {{--<img style="height: 100px" src="{{ asset($goodsModel->img) }}"--}}
                                    {{--alt="{{ $goods->name }}">--}}
                                    {{--@endif--}}


                                    <ul class="products-list product-list-in-box">
                                        <li class="item">
                                            <div class="product-img">
                                                <img style="height: 90px" src="{{ asset($goodsModel['img']) }}"
                                                     alt="{{ $goodsModel['goods_name'] }}">
                                            </div>
                                            <div class="product-info">
                                                <a href="{{ route('goods.info', $goodsModel['id']) }}"
                                                   class="product-title">
                                                    {{ $goodsModel['name'] }}
                                                </a>
                                                <span class="product-description">
                                                    <span style="margin-right: 5px"><span
                                                                style="color: black;">单价：</span>￥{{ $goodsModel['price'] }}</span>
                                                    <span style="margin-left: 5px">
                                                        <div class="weui-cell goods_num_adjust">
                                                            <span class="pull-left" style="color: black;">数量：</span>
                                                            <span onclick="cart_reduce(this);"
                                                                  data-goodsId="{{ $goodsModel['id'] }}"
                                                                  class="weui-number weui-number-sub needsclick">
                                                                <i class="icon-font icon-minus"></i>
                                                            </span>
                                                            <input readonly onchange="cartCount(this);" pattern="[0-9]*"
                                                                   data-goodsId="{{ $goodsModel['id'] }}"
                                                                   type="number" class="weui-number-input "
                                                                   id="cart_count_{{ $goodsModel['id'] }}"
                                                                   value="{{ $goods->qty }}" data-min="1"
                                                                   data-max="10" data-step="1">
                                                            <span onclick="cart_increase(this);"
                                                                  data-goodsId="{{ $goodsModel['id'] }}"
                                                                  class="weui-number weui-number-plus needsclick">
                                                                <i class="icon-font icon-plus"></i>
                                                            </span>
						                            </div>
                                                    </span>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </td>
                                <td>{{ $goodsModel->bar_code }}</td>
                                <td>{{ $goodsModel->supply_company }}</td>
                                <td>{{ $goodsModel->description }}</td>
                                <td>
                                    <a href="{{ route('goods.info', $goods->id) }}"
                                       class="btn btn-flat btn-sm btn-default"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="查看商品">
                                        <i class="fa fa-file-text-o"></i>
                                    </a>

                                    <a class="btn btn-sm btn-flat bg-red" onclick="removeCart({{ $goods->id }})"
                                            data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="移出购物车">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                                <input type="hidden" name="goods-id" value="{{ $goods->id }}">
                                <input type="hidden" name="quantity-{{ $goods->id }}" value="{{ $goods->qty }}">
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </form>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <button class="btn btn-danger pull-left margin-r-5" onclick="clearCart()">
                    清空购物车
                </button>
                @if($cartCollections->count() > 0)
                    <a class="btn btn-primary pull-right" onclick="submitOrder()">立即下单</a>
                @else
                    <a class="btn btn-primary pull-right disabled" disabled="true" href="confirmOrder()">确认订单</a>
                @endif
                <div class="pull-right margin-r-5">
                    <span>共 <i id="cart-count">{{ $cartCollections->count() }}</i>件，总价 <i id="cart-amount">{{ $cartCollections->total() }}</i> 元</span>
                </div>

                <input type="hidden" name="order-total" value="{{ $cartCollections->total() }}">
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>
    <script>
      //移除购物车
      function removeCart(goodsId) {
        var quantity = 1;
        var data = {id: goodsId}

        postRequest('/cart/remove-cart', data);
      }

      function cart_reduce(elem) {
        var goodsId = $(elem).data('goodsid');

        var cartCount = parseInt($('#cart_count_' + goodsId).val());
        if (cartCount == 1) {
          swal('商品数量不能小于1');
          return;
        }
        cartCount -= 1;

        $.post('/cart/cart-reduce', {id: goodsId, qty: 1}, function (response) {
          if (response.errCode == 0) {
            $('#cart_count_' + goodsId).val(cartCount);
            $('#cart-amount').text(response.data.price);
            $('#cart-count').text(response.data.num);
            $('input[name=quantity-' + goodsId + ']').val(cartCount);
            $('input[name=order-total]').val(response.data.price);
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function cartCount(elem) {
        var goodsId = $(elem).data('goodsid');
        var qty = parseInt($(elem).val());
        if (isNaN(qty)) {
          $(elem).val(1);
        }
        if (qty <= 1) {
          swal('商品数量不能小于1');
          $(elem).val(1);
          return
        }

        $.post('/cart/cart-count', requestData, function (response) {
          if (response.errCode == 0) {
            $('#cart-amount').text(response.data.price);
            $('#cart-count').text(response.data.num);
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function cart_increase(elem) {
        var goodsId = $(elem).data('goodsid');
        var qty = parseInt($('#cart_count_' + goodsId).val());

        var cartQty = qty + 1;
        $.post('/cart/cart-increase', {id: goodsId, qty: 1}, function (response) {
          if (response.errCode == 0) {
            $('#cart_count_' + goodsId).val(cartQty);
            $('input[name=quantity-' + goodsId + ']').val(cartQty);
            $('#cart-amount').text(response.data.price);
            $('#cart-count').text(response.data.num);
            $('input[name=order-total]').val(response.data.price)
          } else {
            swal('', response.message, 'error');
          }
        });
      }

      function cartChangeRequest(goodsId) {
        $.post(requestUrl, requestData, function (response) {
          if (response.errCode == 0) {

          } else {
            swal('', response.message, 'error');
          }
        });
      }

      //清空购物车
      function clearCart() {
        postRequest('/cart/clear-cart');
      }

      function submitOrder() {

        var orderData = [];
        var GoodsIdDoms = $('input[name=goods-id]');

        if (GoodsIdDoms.length > 1) {
          for (var i = 0; i < GoodsIdDoms.length; i++) {
            var goodsId = $(GoodsIdDoms[i]).val();
            var goods = {
              goodsId: goodsId,
              qty: $('input[name=quantity-' + goodsId + ']').val()
            }
            orderData.push(goods);
          }
        } else {
          var goodsId = GoodsIdDoms.val();
          var qty = $('input[name=quantity-' + goodsId + ']').val()
          orderData.push({goodsId: GoodsIdDoms.val(), qty: qty});
        }

        postRequest('/order/create', {data: orderData, total: $('input[name=order-total]').val()}, '/order/list');
        return false;
      }

    </script>
@endsection