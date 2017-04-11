@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <img src="{{ asset('/imgs/login_logo.png') }}" alt="">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">登录</div>
                <div class="panel-body">
                    <div class="col-md-6" style="border-right: 1px solid #f6f6f6;">
                        <div class="notice">
                            <div class="notice-title">
                                <i class="notice-icon"></i>
                                <span>登录须知</span>
                            </div>
                            <ul class="notice-list">
                                <li class="list-item ellipsis">
                                    <i class="rt-icon"></i>
                                    登录前，请确认已到郑州市烟草公司完成
                                </li>
                                <li class="list-item ellipsis">
                                    　　              信息注册；
                                </li>
                                <li class="list-item ellipsis">
                                    <i class="rt-icon"></i>
                                    请您设置安全的登录密码，并定期更改；
                                </li>
                                <li class="list-item ellipsis">
                                    <i class="rt-icon"></i>
                                    为保护您的个人信息，请不要向任何人透
                                </li>
                                <li class="list-item ellipsis">
                                    　　              露您的密码；
                                </li>
                                <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></ul>
                        </div>
                    </div>
                    <form class="form-horizontal col-md-6" style="padding-top: 30px" role="form" method="POST" action="{{ url('/login') }}">

                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-2 control-label">邮箱</label>

                            <div class="col-md-10">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-2 control-label">密码</label>

                            <div class="col-md-10">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 记住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> 登录
                                </button>

                                {{--<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
