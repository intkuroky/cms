@extends('layouts.main')
@section('content')
    <div class="content">

        <div class="box page-filter-form">
            <form class="form-horizontal" novalidate="" method="get">
                <div class="box-header with-border">
                    <h4>商品搜索</h4>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="username" class="col-sm-3 control-label padding-left-big">商品名称</label>

                                <div class="col-sm-9">
                                    <input id="name" type="text" class="form-control" placeholder="请输入商品名称" name="name"
                                           value="{{ isset($filters['name'])?$filters['name']:'' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-3 control-label padding-left-big">生产厂商</label>

                                <div class="col-sm-9">
                                    <input id="mobile" type="text" class="form-control" placeholder="请输入生产厂商名称" name="company"
                                           value="{{ isset($filters['company'])?$filters['company']:'' }}">
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
                                        <input type="text" class="form-control" name="datetime" id="date-pick" placeholder="点击选择日期区间" readonly=""
                                               value="{{ isset($filters['datetime'])?$filters['datetime']:'' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary" ng-click="searchBuyer()">搜索</button>
                    <a href="{{ url('goods/list') }}" class="btn btn-sm btn-default" target="_self">清除搜索</a>
                </div>

                </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">商品列表</h3>
                @if(\Auth::user()->hasRole('stock'))
                <div>
                    <a href="{{ route('goods.create') }}" class="btn btn-primary pull-right">添加商品</a>
                </div>
                @endif
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 1%">#</th>
                        <th style="width: 30%">商品名称</th>
                        {{--<th>预览图</th>--}}
                        <th style="width: 5%">价格</th>
                        <th style="width: 10%">条形码</th>
                        <th style="width: 15%">生产厂商</th>
                        <th style="width: 20%">描述</th>
                        <th style="width: 10%">操作</th>
                    </tr>
                    @foreach ($goodsCollection as $goods)
                        <tr>
                            <td>{{ $goods['id'] }}</td>
                            {{--<td>{{ $goods['name'] }}</td>--}}
                            <td>
                                <ul class="products-list product-list-in-box">
                                    <li class="item">
                                        <div class="product-img">
                                            <img style="height: 100px" src="{{ asset($goods['img']) }}"
                                                 alt="{{ $goods['goods_name'] }}">
                                        </div>
                                        <div class="product-info">
                                            <a href="{{ route('goods.info', $goods['goods_id']) }}"
                                               class="product-title">
                                                {{ $goods['name'] }}
                                            </a>
                                            <span class="product-description">
                                            <span style="margin-right: 5px">￥{{ $goods['price'] }}</span>，
                                                <span style="margin-left: 5px">库存{{ $goods['store_num'] }}件</span>
                                        </span>
                                        </div>
                                    </li>
                                </ul>
                            </td>
                            <td>{{ $goods['price'] }}</td>
                            <td>{{ $goods['bar_code'] }}</td>
                            <td>{{ $goods['supply_company'] }}</td>
                            <td>{{ $goods['description'] }}</td>
                            <td>
                                <a href="{{ route('goods.info', $goods['id']) }}" class="btn btn-flat btn-sm btn-default" data-toggle="tooltip" data-placement="top"
                                   data-original-title="查看商品">
                                    <i class="fa fa-file-text-o"></i>
                                </a>

                                @if(\Auth::user()->hasRole('stock'))
                                <a href="{{ route('goods.edit', $goods['id']) }}" class="btn btn-flat btn-sm btn-primary" data-toggle="tooltip" data-placement="top"
                                   data-original-title="编辑商品">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <button class="btn btn-sm btn-flat bg-red" onclick="deleteGoods({{ $goods['id'] }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除商品">
                                    <i class="fa fa-remove"></i>
                                </button>
                                @endif

                                @if(\Auth::user()->hasRole('customer'))
                                <a class="btn btn-sm btn-flat bg-aqua" data-toggle="tooltip"
                                   data-placement="top" data-original-title="加入购物车" onclick="joinCart({{ $goods['id'] }})">
                                    <i class="fa fa-shopping-cart"></i>
                                </a>
                                @endif

                                <input type="hidden" name="goods-id" value="{{ $goods->id }}">
                                <input type="hidden" name="quantity-{{ $goods->id }}" value="{{ $goods->qty }}">
                                <input type="hidden" name="order-{{ $goods->id }}-total" value="{{ $goods->price }}">
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
                    @for($i = 1; $i <= $goodsCollection->lastPage(); $i++)
                        @if($i==$goodsCollection->currentPage())
                            <li class="active"><a href="">{{ $i }}</a></li>
                        @else
                            <li><a href="{{ $goodsCollection->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    <li><a href="#">»</a></li>
                </ul>
                <div class="pull-right" style="margin-right: 10px;display:inline-block;float: right;line-height: 30px;">
                    共{{ $goodsCollection->total() }}项，{{ $goodsCollection->lastPage() }}页
                </div>
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/shopping-cart.js') }}"></script>

    <script>
      $(function () {

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
      function joinCart(id) {
        var quantity = 1;
        var data = {
          id: id,
          qty: 1,
          goodsName: '{{ $goodsCollection['name'] }}',
          price: '{{ $goodsCollection['price'] }}'
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
      
      function deleteGoods(goodsId) {
        $.post('/goods/delete/' + goodsId, function (response) {
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