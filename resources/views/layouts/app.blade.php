<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>烟草物流信息管理系统</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"
          integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }

        .notice-list {
            margin-top: 12px;
            padding: 0 30px;
            list-style: none;
        }

        .notice {
            float: left;
            width: 354px;
            height: 300px
        }

        .notice .notice-title {
            height: 30px;
            line-height: 30px
        }

        .notice .notice-title .notice-icon {
            float: left;
            margin-top: 6px;
            margin-left: 30px;
            margin-right: 6px;
            display: inline-block;
            width: 20px;
            height: 20px;
            background: url({{ asset('/imgs/login_lb.png') }}) no-repeat
        }

        .notice .notice-title span {
            float: left;
            font-size: 24px;
            color: #fd9604
        }

        .notice .notice-list {
            margin-top: 12px;
            padding: 0 30px
        }

        .notice .notice-list .list-item a {
            color: #1f1f1f;
            text-decoration: none
        }

        .notice-list .list-item {
            height: 28px;
            line-height: 28px;
            font-size: 14px;
            color: #1f1f1f;
            cursor: pointer;
        }

        .notice .notice-list .list-item .rt-icon {
            float: left;
            width: 4px;
            height: 7px;
            margin-top: 10px;
            margin-right: 6px;
            background: url({{ asset('/imgs/login_list.png') }}) no-repeat
        }

        .copyright {
            width: 100%;
            bottom: 0px;
            background: #f8f8f8;
            font-size: 13px;
            text-align: center;
            color: #555555;
            padding-top: 28px;
            padding-bottom: 28px;
            border-top: 1px solid #e7e7e7;
        }

        .app-layout {
            min-height: calc(100vh);
        }

    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                烟草物流信息管理系统
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
        {{--<ul class="nav navbar-nav">--}}
        {{--<li><a href="{{ url('/home') }}">Home</a></li>--}}
        {{--</ul>--}}

        <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">登录</a></li>
                    {{--<li><a href="{{ url('/register') }}">Register</a></li>--}}
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')


<!-- JavaScripts -->

<div class="copyright">
    <div class="container col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <span>Copyright © <a href="">烟草物流信息管理系统</a></span> |
                <span><a href="http://www.miibeian.gov.cn/" target="_blank">京ICP备xxxxxx号</a></span>
            </div>
            <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"
        integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
