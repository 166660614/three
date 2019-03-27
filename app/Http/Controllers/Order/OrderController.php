<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\OrderModel;
use App\Model\CartModel;
class OrderController extends Controller
{
    public function createOrder(){
        $user_id=$_POST['user_id'];
        $goods_id=$_POST['goods_id'];
        $cart_id=$_POST['cart_id'];
        $order_num=time().random_int(5);
        $order_data=[
            'order_num'=>$order_num,
            'goods_id'=>$goods_id,
            'user_id'=>$user_id,
        ];
        $res=OrderModel::insert($order_data);
        if($res){
            $cart_where=[
                'cart_id'=>$cart_id
            ];
            CartModel::where($cart_where)->update(['is_delete'=>2]);
            $res_data=[
                'errcode'=>4001,
                'errmsg'=>'ok'
            ];
        }else{
            $res_data=[
                'errcode'=>5001,
                'errmsg'=>'no'
            ];
        }
        return $res_data;
    }
}
