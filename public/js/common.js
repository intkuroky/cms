/**
 * Created by WangSF on 2017/3/15.
 */

$(function () {
  $("[data-mask]").inputmask();
})



$.ajaxSetup({
    complete: function (xhr, result) {
      if (xhr && xhr.status == 422 && xhr.responseText) {
        var errorMsg = JSON.parse(xhr.responseText);
        var firstMsg = '';
        for (key in errorMsg) {
          firstMsg = errorMsg[key];
          break;
        }
        swal('', firstMsg, 'error');
        xhr.abort();
      }

      if (xhr.responseJSON) {
        if (xhr.responseJSON.errNum == 403) {
          // 如果返回403错误码。默认未登录，调用APP的登录方法
          callOCFunctionHandle("goToLogin", "");
          xhr.abort();
        } else if (xhr.responseJSON.errNum == 401) {
          // 如果返回401错误码。默认需要付款，调用APP的进入付款页面
          callOCFunctionHandle("showPayTipViewController", "");
          xhr.abort();
        }
      }
    }
  }
);

function responseSuccess(message, fn) {
  swal({
      title: "",
      text: message,
      type: "success",
      confirmButtonColor: "#7ecff4",
      confirmButtonText: "ok",
      closeOnConfirm: true
    },
    function () {
      if (typeof fn == 'function') fn();
    });
}

function postRequest(requestUrl, requestData, redirectUrl) {
  requestData = requestData || {};
  $.post(requestUrl, requestData, function (response) {
    if (response.errCode == 0) {
      responseSuccess(response.message, function () {

        if(redirectUrl){
          if(typeof redirectUrl == 'function'){
            redirectUrl();
            return;
          }else {
            window.location.href = redirectUrl;
            return;
          }
        }

        window.location.reload();
      });
    } else {
      swal('', response.message, 'error');
    }
  });
}