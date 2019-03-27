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

Route::post('/user/login','User\UserController@login');//用户登录接口
Route::post('/user/cart','Cart\CartController@cartShow');//购物车数据接口
Route::post('/cart/join','Cart\CartController@cartJoin');//购物车数据接口
