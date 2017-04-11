<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('imgs/avatar.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $authUser->name }}</p>
                <!-- Status -->
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">

        @if(\Auth::user()->hasRole('admin'))
            <!-- 系统管理 ./begin -->
            <li class="header">系统管理</li>

            <li class="treeview {{ Route::is('roles.list')|| Route::is('permission.list') ? 'active' : ''}}">
                <a href="#"><i class="fa fa-link"></i> <span>权限管理</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('roles.list')?'active' : ''}}"><a href="{{ route('roles.list') }}">角色管理</a></li>
                    <li  class="{{ Route::is('permission.list')?'active' : ''}}"><a href="{{ route('permission.list') }}">权限列表</a></li>
                </ul>
            </li>
            <li class="{{ Route::is('user.list')?'active' : ''}}"><a href="{{ route('user.list') }}"><i class="fa fa-link"></i> <span>人员管理</span></a></li>
            <li class="{{ Route::is('system.log')?'active' : ''}}"><a href="{{ route('system.log') }}"><i class="fa fa-link"></i> <span>操作日志</span></a></li>
        @endif
        @if(\Auth::user()->hasRole('customer'))
            <li class="header">客户订货</li>
            <!-- 客户订货 -->
            <li class="treeview {{ Route::is('user.info')|| Route::is('goods.list') || Route::is('cart.list') || Route::is('order.list') ? 'active' : ''}}">
                <a href="#"><i class="fa fa-link"></i> <span>客户订货</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('user.info')?'active' : ''}}"><a href="{{ route('user.info') }}">个人信息</a></li>
                    <li class="{{ Route::is('goods.list')?'active' : ''}}"><a href="{{ route('goods.list') }}">浏览商品</a></li>
                    <li class="{{ Route::is('cart.list')?'active' : ''}}"><a href="{{ route('cart.list') }}">购物车</a></li>
                    <li class="{{ Route::is('order.list')?'active' : ''}}"><a href="{{ route('order.list') }}">我的订单</a></li>
                </ul>
            </li>
        @endif

        @if(\Auth::user()->hasRole('stock'))
            <!-- 仓储中心 -->
            <li class="header">仓储中心</li>
            <li class="treeview {{ Route::is('goods.list') || Route::is('stock.order')?'active' : ''}}">
                <a href="#"><i class="fa fa-link"></i> <span>仓储中心</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('goods.list')?'active' : ''}}"><a href="{{ route('goods.list') }}">商品管理</a></li>
                    <li class="{{ Route::is('stock.order')?'active' : ''}}"><a href="{{ route('stock.order') }}">出库管理</a></li>
                </ul>
            </li>
        @endif

        @if(\Auth::user()->hasRole('seller'))
            <!-- 营销中心 -->
            <li class="header">营销中心</li>
            <li class="treeview {{ Route::is('goods.list') || Route::is('seller.order') ?'active' : ''}}">
                <a href="#"><i class="fa fa-link"></i> <span>营销中心</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('goods.list')?'active' : ''}}"><a href="{{ route('goods.list') }}">浏览商品</a></li>
                    <li class="{{ Route::is('seller.order')?'active' : ''}}"><a href="{{ route('seller.order') }}">订单管理</a></li>
{{--                    <li class="{{ Route::is('stock.ordert')?'active' : ''}}"><a href="#">签收单管理</a></li>--}}
                </ul>
            </li>
        @endif

        @if(\Auth::user()->hasRole('deliever'))
            <!-- 配送中心 -->
            <li class="header">配送中心</li>
            <li class="treeview {{ Route::is('delivery.*')?'active' : ''}}">
                <a href="#"><i class="fa fa-link"></i> <span>配送中心</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('delivery.man')?'active' : ''}}"><a href="{{ route('delivery.man') }}">送货员信息查询</a></li>
                    <li class="{{ Route::is('delivery.car')?'active' : ''}}"><a href="{{ route('delivery.car') }}">车辆信息查询</a></li>
                    <li class="{{ Route::is('delivery.order')?'active' : ''}}"><a href="{{ route('delivery.order') }}">配送单管理</a></li>
                </ul>
            </li>
        @endif

        @if(\Auth::user()->hasRole('picker'))
            <!-- 分拣中心 -->
            <li class="header">分拣中心</li>
            <li class="treeview {{ Route::is('pick.*')?'active' : ''}}">
                <a href="#"><i class="fa fa-link"></i> <span>分拣中心</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('pick.unpick-order')?'active' : ''}}"><a href="{{ route('pick.unpick-order') }}">未分拣订单</a></li>
                    <li class="{{ Route::is('pick.picked-order')?'active' : ''}}"><a href="{{ route('pick.picked-order') }}">已分捡订单</a></li>
                </ul>
            </li>
        @endif
        </ul>
        <!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
</aside>