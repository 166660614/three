<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/goods/list','Goods\GoodsController@goods');   //商品数据接口
Route::post('/goods/details','Goods\GoodsController@details');   //商品详情数据接口
Route::post('/goods/salenum','Goods\GoodsController@salevalue');   //添加访问量数据接口
Route::post('/goods/getsal','Goods\GoodsController@getsal');   //访问量数据接口
Route::post('/user/login','User\UserController@login');//用户登录接口
Route::post('/user/register','User\UserController@register');//用户注册接口
Route::post('/user/center','User\UserController@center');//个人中心判断是否登录接口
Route::post('/user/cart','Cart\CartController@cartShow');//购物车数据接口
Route::post('/user/dot','User\DotController@dot');//点赞数据接口
Route::post('/user/give','User\DotController@give');//点赞展示数据接口
Route::post('/cart/join','Cart\CartController@cartJoin');//添加到购物车


Route::post('/collect/add','User\CollectController@collectAdd');//收藏商品数据接口
Route::post('/collect/zrange','User\CollectController@collectZrange');//商品收藏展示页面接口
Route::post('/order/add','Order\OrderController@createOrder');//生成(添加)订单数据接口
Route::post('/order/show','Order\OrderController@orderShow');//订单数据接口
Route::post('/end/list','User\DotController@end');