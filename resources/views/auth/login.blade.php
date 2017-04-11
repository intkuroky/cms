<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <title>卷烟物流信息管理系统</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/css.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/plugins/jqueryui/default/jquery-ui-1.9.2.custom.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/plugins/jqgrid/css/ui.jqgrid.css') }}"/>
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/platform/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/plugins/jqgrid/css/css/redmond/jquery-ui-1.8.16.custom.css')}}"/>
    <link rel="stylesheet" href="{{ asset("/bower_components/sweetalert/dist/sweetalert.css")}}">

    <script src="{{ asset('/js/jquery1.9.0.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/sdmenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/laydate/laydate.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/plugins/jqueryui/jquery-ui-1.9.2.custom.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/plugins/jqgrid/js/jquery.jqGrid.src.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/plugins/jqgrid/js/i18n/grid.locale-cn.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/plugins/datepicker/widget.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/plugins/datepicker/jquery.ui.datepicker-zh-CN.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/plugins/datepicker/jquery-ui-timepicker-addon.js')}}"></script>

    <script src="{{ asset("/bower_components/sweetalert/dist/sweetalert.min.js") }}"></script>
    <style type="text/css">
    </style>
</head>
<body>
<div class="left-bg"></div>
<div class="right-bg"></div>
<div class="login-logo"></div>
<div class="login-center">
    <div class="login-content">
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
            </ul>
        </div>
        <form id="login-form" role="form" method="POST" action="{{ url('/login') }}">
            <div class="login-input">
                <div class="login-label">用户登录</div>
                <div class="username">
                    <i class="username-icon"></i>
                    <input type="text" class="" name="name" value="admin" style="color: black" placeholder="用户名"/>
                </div>
                <div class="password">
                    <i class="password-icon"></i>
                    <input type="password" class="" name="password" style="color: black" value="12345678"
                           placeholder="密码"/>
                </div>
                <div class="login-btn1" onclick="$('#login-form').submit();">马上登录</div>
                <div class="login-btn2"><a href="javascript:;" class="forget">忘记密码</a></div>

            </div>
        </form>
    </div>
</div>

<div id="revisePsdDialog" class="forgetDialog">
    <div class="one">
        <form method="post" action="#">
            <label><span>手机号码</span>
                <input type="text" name="phone" value=""><em></em>
            </label>
            <label class="tips tips3">
                <span>短信验证码</span>
                <input type="text" name="verify-code">
                <em></em>
            </label>
            <label class="tips tips4">
                <span></span>
                <a id="sendButton" href="javascript:sendVerifyCode();" class="getMessage">免费获取短信验证码
                </a>

            </label>
            <label class="tips">
                <span></span>
                <em class="longEm">请查收手机短信，并填写短信中的验证码</em></label>
            <label><span></span>
                <input type="button" name="" value="下一步" onclick="checkVerifyCode()" class="next">
                <em></em></label>
        </form>
    </div>
    <div class="two">
        <h2>重置登录密码</h2>
        <form method="post" action="#">
            <label><span>用户名</span><input type="text" id="username" value="" readonly><em></em></label>
            <label><span>新密码</span><input type="password" id="password"><em>注：密码长度不超过16位</em></label>
            <label><span>确认新密码</span><input type="password" id="confirm-password" name=""><em></em></label>
            <label><span></span><input type="button" onclick="passwordRest()" name="" value="确定" class="sub"><em></em></label>
        </form>
    </div>
</div>
<script type="text/javascript">


  var sendDisabled = false;

  @if($errors->has('name') || $errors->has('password'))
  swal({
    title: '',
    text: '用户名或密码错误',
    type: 'error'
  });
  @endif

  function sendVerifyCode() {
    if(sendDisabled){
      return;
    }

    var phone = $('input[name=phone]').val();
    if (!phone || (phone.length < 11)) {
      swal('', '请输入正确手机号', 'warning');
      return;
    }

    $.post('/send-verify-code', {phone: phone}, function (response) {
      if (response.errCode == 0) {
        swal('', response.message, 'success');
        timer(60);
        return ;
      }
      swal('', response.message, 'warning');
      return;
    });
  }

  function checkVerifyCode() {
    var phone = $('input[name=phone]').val();
    var verifyCode = $('input[name=verify-code]').val();

    if (!phone || (phone.length < 11)) {
      swal('', '请输入正确手机号', 'warning');
      return;
    }

    if (!verifyCode || verifyCode.length != 6) {
      swal('', '请输入正确的6位验证码', 'warning');
      return;
    }

    $.post('/check-verify-code', {phone: phone, verifyCode: verifyCode}, function (response) {
      if (response.errCode == 0) {
        $('#username').val(response.data.name);
        $(".forgetDialog").find(".one").hide();
        $(".forgetDialog").find(".two").show();
        return true;
      }
      swal('', response.message, 'warning');
      return;
    });
  }

  function timer(seconds){
    var elem = $('#sendButton');
    if(seconds >= 0){
      sendDisabled = true;
      setTimeout(function(){
        //显示倒计时
        elem.html(seconds + '秒后重新获取');
        //递归
        seconds -= 1;
        timer(seconds);
      }, 1000);
    }else{
      sendDisabled = false;
      elem.html('免费获取短信验证码');
    }
  }
  
  function passwordRest() {
    var password = $('#password').val();
    var confirmPassword = $('#confirm-password').val();
    var name = $('#username').val();
    if(!name || !password){
      swal('', '请检查必填项', 'warning');
      return;
    }

    if(password != confirmPassword){
      swal('', '两次输入的密码必须一致', 'warning');
      return;
    }
    var data = {
        name: $('#username').val(),
        passwd: $('#password').val()
    };
    $.post('/reset-passwd', data, function (response) {
      if (response.errCode == 0) {
        swal('', response.message, 'success');
        window.location.href = '/';
        return
      }
      swal('', response.message, 'warning');
      return;
    });

  }

  $(function () {
    $(".forget").on("click", function () {
      $("#revisePsdDialog").dialog({
        dialogClass: "forgetDialog",
        width: 610,
        height: 450
      });
    })
  })
</script>
</body>
</html>
