@extends('layouts.main')
@section('content')
    <div class="content col-md-12">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">商品信息</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                @foreach($goodsCollections as $goodsCollection)
                    <div class="col-md-4">
                        <img style="height: 100px" src="{{ asset('imgs/big/01.jpg') }}"
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
                    </div>

                    <div class="col-md-7">
                        <label class="col-md-3 no-padding">商品价格：￥{{ $goodsCollection['price'] }}</label>
                        <label class="col-md-3 no-padding">商品数量：￥{{ $quantity }}</label>
                    </div>
                @endforeach
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <button type="button" class="btn btn-default pull-left">返回</button>
                <a class="btn btn-primary pull-right">立即购买</a>
                <input type="button" class="btn btn-success pull-right margin-r-5" onclick="submitOrder()" value="提交订单">
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