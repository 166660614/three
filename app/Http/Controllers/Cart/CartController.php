<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function cartShow(){
        $user_id=$_POST['user_id'];
        $cart_where=[
            'user_id'=>$user_id,
            'is_delete'=>1
        ];
        $cart_data=CartModel::join('api_goods','api_goods.goods_id','=','api_cart.goods_id')->where($cart_where)->get();
        return $cart_data;
    }
}