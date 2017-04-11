@extends('layouts.main')
@section('styles')
    <style>
    input[readonly]{
        background-color: #fff !important;
    }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box page-filter-form">
                    <form class="form-horizontal ng-pristine ng-valid" novalidate="">
                        <div class="box-header with-border">
                            <h4>订单详情</h4>
                        </div>
                        <div class="box-body">

                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="username"
                                               class="col-sm-3 control-label padding-left-big">订单编号</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control no-border" readonly value="{{ $order['order_no'] }}">
                                        </div>
                                    </div>
                                </div>
                                @if(isset($order['price']))
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="mobile"
                                                   class="col-sm-3 control-label padding-left-big">订单价格</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control no-border" readonly value="￥{{ $order['price'] }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="mobile"
                                               class="col-sm-3 control-label padding-left-big">用户名</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control no-border" readonly value="{{ $orderUser? $orderUser['name']: '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="mobile"
                                               class="col-sm-3 control-label padding-left-big">收货人</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control no-border" readonly value="{{ $orderUser && $orderUser['display_name']? $orderUser['display_name']:'无' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="mobile"
                                               class="col-sm-3 control-label padding-left-big">联系方式</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control no-border" readonly value="{{ $orderUser? $orderUser['phone']:'无' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="mobile"
                                               class="col-sm-3 control-label padding-left-big">送货地址</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control no-border" readonly value="{{ $orderUser->customerInfo? $orderUser->customerInfo['company_address']:'无' }}">
                                        </div>
                                    </div>
                                </div>

                                @if(isset($order['comments']))
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="mobile"
                                                   class="col-sm-1 control-label padding-left-big">订单评价</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control no-border" readonly value="{{ $order['comments']? :'无' }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>

                    </form>
                </div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">订单商品详情</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">

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
                                    </tr>
                                    <?php $index = 1; ?>
                                    @foreach($order['orderGoods'] as $orderGoods)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <?php $index++; ?>
                                            <td>
                                                <?php $goodsModel = \App\Models\Goods::find($orderGoods['goods_id'])?>
                                                {{--@if($goodsModel)--}}
                                                {{--<img style="height: 100px" src="{{ asset($goodsModel->img) }}"--}}
                                                {{--alt="{{ $goods->name }}">--}}
                                                {{--@endif--}}


                                                <ul class="products-list product-list-in-box">
                                                    <li class="item">
                                                        <div class="product-img">
                                                            <img style="height: 90px" src="{{ asset($orderGoods['img']) }}"
                                                                 alt="{{ $orderGoods['goods_name'] }}">
                                                        </div>
                                                        <div class="product-info">
                                                            <a href="{{ route('goods.info', $orderGoods['id']) }}"
                                                               class="product-title">
                                                                {{ $orderGoods['goods_name'] }}
                                                            </a>
                                                            <span class="product-description">
                                                    <span style="margin-right: 5px"><span
                                                                style="color: black;">单价：</span>￥{{ $orderGoods['price'] }}</span>
                                                    <span style="margin-left: 5px">
                                                        <div class="weui-cell goods_num_adjust">
                                                            <span class="pull-left" style="color: black;">数量：{{ $orderGoods['quantity'] }}</span>
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
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
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
    <script>
      function submitOrder() {

      }
    </script>
@endsection