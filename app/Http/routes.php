<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group([
//    'middleware' => 'OAuth',     //中间件
//    'namespace' => '\App\CongCNN\Features\Account\Controllers'
], function () {

    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    Route::get('/', 'IndexController@index');

    //发送短信验证码
    Route::post('send-verify-code', [
        'as'   => 'send-verify-code',
        'uses' => 'IndexController@sendVerifyCode',
    ]);

    Route::post('check-verify-code', [
        'as'   => 'check-verify-code',
        'uses' => 'IndexController@checkVerifyCode',
    ]);

    Route::post('reset-passwd', [
        'as'   => 'reset-passwd',
        'uses' => 'IndexController@resetPasswd',
    ]);

    //商品列表
    Route::get('goods/list', [
        'as'   => 'goods.list',
        'uses' => 'GoodsController@goodsList',
    ]);

    Route::post('goods/delete/{id}', [
        'as'   => 'goods.delete',
        'uses' => 'GoodsController@goodsDelete',
    ]);

    Route::get('goods/create', [
        'as'   => 'goods.create',
        'uses' => 'GoodsController@goodsCreate',
    ]);

    Route::post('goods/create', [
        'as'   => 'goods.save',
        'uses' => 'GoodsController@goodsSave',
    ]);

    Route::post('goods/update/{goodsId}', [
        'as'   => 'goods.update',
        'uses' => 'GoodsController@goodsUpdate',
    ]);

    Route::any('goods/edit/{goodsId}', [
        'as'   => 'goods.edit',
        'uses' => 'GoodsController@goodsEdit',
    ]);

    Route::get('goods/info/{id}', [
        'as'   => 'goods.info',
        'uses' => 'GoodsController@goodsInfo',
    ]);

    //权限管理
    //角色管理
    Route::get('roles/list', [
        'as'   => 'roles.list',
        'uses' => 'RolesController@rolesList',
    ]);
    Route::post('roles/add', [
        'as'   => 'roles.add',
        'uses' => 'RolesController@rolesAdd',
    ]);
    Route::get('roles/edit/{id}', [
        'as'   => 'roles.edit',
        'uses' => 'RolesController@rolesEdit',
    ]);
    Route::post('roles/update/{id}', [
        'as'   => 'roles.update',
        'uses' => 'RolesController@rolesUpdate',
    ]);
    Route::post('roles/delete/{id}', [
        'as'   => 'roles.delete',
        'uses' => 'RolesController@rolesDelete',
    ]);

    // 权限列表
    Route::get('permission/list', [
        'as'   => 'permission.list',
        'uses' => 'PermissionController@permissionList',
    ]);
    Route::post('permission/add', [
        'as'   => 'permission.add',
        'uses' => 'PermissionController@permissionAdd',
    ]);
    Route::get('permission/edit/{id}', [
        'as'   => 'permission.edit',
        'uses' => 'PermissionController@permissionEdit',
    ]);
    Route::post('permission/update/{id}', [
        'as'   => 'permission.update',
        'uses' => 'PermissionController@permissionUpdate',
    ]);
    Route::post('permission/delete/{id}', [
        'as'   => 'permission.delete',
        'uses' => 'PermissionController@permissionDelete',
    ]);

    // 人员管理
    Route::get('user/list', [
        'as'   => 'user.list',
        'uses' => 'UserController@userList',
    ]);
    Route::get('user/info/{userId?}', [
        'as'   => 'user.info',
        'uses' => 'UserController@userInfo',
    ]);

    Route::post('user/add', [
        'as'   => 'user.add',
        'uses' => 'UserController@userAdd',
    ]);

    Route::post('manager/add', [
        'as'   => 'manager.add',
        'uses' => 'UserController@managerAdd',
    ]);

    Route::get('user/edit/{id}', [
        'as'   => 'user.edit',
        'uses' => 'UserController@userEdit',
    ]);
    Route::post('user/update/{id}', [
        'as'   => 'user.update',
        'uses' => 'UserController@userUpdate',
    ]);
    Route::post('user/delete/{id}', [
        'as'   => 'user.delete',
        'uses' => 'UserController@userDelete',
    ]);

    //操作日志
    Route::get('system-log', [
        'as'   => 'system.log',
        'uses' => 'SystemController@logs',
    ]);

    //客户订单
    //个人信息
    Route::get('customer/info', [
        'as'   => 'customer.info',
        'uses' => 'CustomerController@customerInfo',
    ]);

    Route::post('customer/update/{id}', [
        'as'   => 'customer.update',
        'uses' => 'CustomerController@customerUpdate',
    ]);

    // 购物车
    Route::post('cart/join-cart', [
        'as'   => 'cart.join-cart',
        'uses' => 'CartController@joinCart',
    ]);

    Route::post('cart/remove-cart', [
        'as'   => 'cart.remove-cart',
        'uses' => 'CartController@cartRemove',
    ]);

    Route::post('cart/clear-cart', [
        'as'   => 'cart.clear-cart',
        'uses' => 'CartController@cartClear',
    ]);

    Route::get('cart/list', [
        'as'   => 'cart.list',
        'uses' => 'CartController@cartList',
    ]);

    Route::post('cart/cart-reduce', [
        'as'   => 'cart.cart-reduce',
        'uses' => 'CartController@cartReduce',
    ]);

    Route::post('cart/cart-count', [
        'as'   => 'cart.cart-count',
        'uses' => 'CartController@cartCount',
    ]);

    Route::post('cart/cart-increase', [
        'as'   => 'cart.cart-increase',
        'uses' => 'CartController@cartIncrease',
    ]);

    Route::get('test-cart', [
        'uses' => 'CartController@testCart',
    ]);

    //订单详情
    Route::post('order/create', [
        'as'   => 'order.create',
        'uses' => 'OrderController@orderCreate',
    ]);

    Route::get('order/list', [
        'as'   => 'order.list',
        'uses' => 'OrderController@orderList',
    ]);

    Route::get('order/confirm/{goodsId}/{quantity}', [
        'as'   => 'order.confirm',
        'uses' => 'OrderController@orderConfirm',
    ]);

    Route::post('order/received/{orderId}', [
        'as'   => 'order.received',
        'uses' => 'OrderController@orderReceived',
    ]);

    Route::post('order/comment/{orderId}', [
        'as'   => 'order.comment',
        'uses' => 'OrderController@orderComment',
    ]);

    Route::get('order/info/{orderId}', [
        'as'   => 'order.info',
        'uses' => 'OrderController@orderInfo',
    ]);

    Route::post('order/delete/{id}', [
        'as'   => 'order.delete',
        'uses' => 'OrderController@orderDelete',
    ]);

    // 营销中心
    Route::get('seller/order', [
        'as'   => 'seller.order',
        'uses' => 'SellerController@sellerOrder',
    ]);

    Route::post('seller/reject-order/{orderId}', [
        'as'   => 'seller.reject-order',
        'uses' => 'SellerController@sellerRejectOrder',
    ]);

    Route::post('seller/adopt-order/{orderId}', [
        'as'   => 'seller.adopt-order',
        'uses' => 'SellerController@sellerAdoptOrder',
    ]);

    //仓储中心
    Route::get('stock/order', [
        'as'   => 'stock.order',
        'uses' => 'StockController@stockOrder',
    ]);

    Route::post('stock/order-update/{orderId}/{status}', [
        'as'   => 'stock.order-update',
        'uses' => 'StockController@orderUpdate',
    ]);


    //分拣中心
    Route::get('pick/unpick-order', [
        'as'   => 'pick.unpick-order',
        'uses' => 'PickController@unpickOrder',
    ]);

    Route::get('pick/picked-order', [
        'as'   => 'pick.picked-order',
        'uses' => 'PickController@pickedOrder',
    ]);

    Route::post('pick/order-update/{orderId}/{status}', [
        'as'   => 'pick.order-update',
        'uses' => 'PickController@orderUpdate',
    ]);

    // 配送中心
    Route::get('delivery/order', [
        'as'   => 'delivery.order',
        'uses' => 'DeliveryController@deliveryOrder',
    ]);

    Route::post('delivery/start/{orderId}', [
        'as'   => 'delivery.start',
        'uses' => 'DeliveryController@deliveryStart',
    ]);

    Route::post('delivery/complete/{orderId}', [
        'as'   => 'delivery.complete',
        'uses' => 'DeliveryController@deliveryComplete',
    ]);

    Route::get('delivery/man', [
        'as'   => 'delivery.man',
        'uses' => 'DeliveryController@deliveryMan',
    ]);

    Route::get('delivery/man/edit/{manId}', [
        'as'   => 'delivery.man-edit',
        'uses' => 'DeliveryController@deliveryManEdit',
    ]);

    Route::post('delivery/man/update/{manId}', [
        'as'   => 'delivery.man-update',
        'uses' => 'DeliveryController@deliveryManUpdate',
    ]);

    Route::get('delivery/car/edit/{carId}', [
        'as'   => 'delivery.car-edit',
        'uses' => 'DeliveryController@deliveryCarEdit',
    ]);

    Route::post('delivery/car/update/{carId}', [
        'as'   => 'delivery.car-update',
        'uses' => 'DeliveryController@deliveryCarUpdate',
    ]);

    Route::get('delivery/car', [
        'as'   => 'delivery.car',
        'uses' => 'DeliveryController@deliveryCar',
    ]);

    Route::post('delivery/car/create', [
        'as'   => 'delivery.car-create',
        'uses' => 'DeliveryController@deliveryCarCreate',
    ]);

    Route::post('delivery/man/create', [
        'as'   => 'delivery.man-create',
        'uses' => 'DeliveryController@deliveryManCreate',
    ]);

    Route::post('delivery/car/delete/{manId}', [
        'as'   => 'delivery.car-delete',
        'uses' => 'DeliveryController@deliveryCarDelete',
    ]);

    Route::post('delivery/man/delete/{manId}', [
        'as'   => 'delivery.man-delete',
        'uses' => 'DeliveryController@deliveryManDelete',
    ]);

    Route::post('file/upload', [
        'as' => 'file.upload',
        'uses' => 'FileController@fileUpload'
    ]);
    //Route::get('testsms', 'IndexController@testSms');
    //
    //Route::get('bgm-page', 'IndexController@bgm');

    Route::group([ 'prefix' => 'api' ], function () {

        Route::post('click-reply', 'IndexController@postClickReply');
        Route::post('tab-opened', 'IndexController@tabOpened');
        Route::post('can-not-unlocked', 'IndexController@canNotUnlocked');

    });
});

Route::auth();

//Route::get('/home', 'HomeController@index');
//
//Route::auth();
//
//Route::get('/home', 'HomeController@index');
