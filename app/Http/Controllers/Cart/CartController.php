<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Model\CartModel;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function cartShow(){
        $user_id=$_POST['user_id'];
        $cart_where=[
            '$user_id'=>$user_id,
            'is_delete'=>1
        ];
        $cart_data=CartModel::where($cart_where)->get();
        if(!$cart_data){
            $data=[
                'errcode'=>'4001',
                'errmsg'=>$cart_data,
            ];
            return $cart_data;
        }
    }
}
