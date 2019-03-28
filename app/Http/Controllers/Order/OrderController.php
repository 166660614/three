<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\OrderModel;
use App\Model\CartModel;
use App\Model\GoodsModel;
class OrderController extends Controller
{
    public function createOrder(){
        $user_id=$_POST['user_id'];
        if(empty($user_id)){
            $data=[
                'errcode'=>4001,
                'msg'=>'请先登录'
            ];
            return $data;
        }
        $goods_id=$_POST['goods_id'];
        $cart_id=$_POST['cart_id'];
        $order_num='three'.time();
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
                'errcode'=>0,
                'errmsg'=>'ok'
            ];
        }else{
            $res_data=[
                'errcode'=>5001,
                'msg'=>'no'
            ];
        }
        return $res_data;
    }
    public function orderShow(){
        $user_id=$_POST['user_id'];
        if(empty($user_id)){
            $data=[
                'errcode'=>4001,
                'msg'=>'请先登录'
            ];
            return $data;
        }
        $order_where=[
            'user_id'=>$user_id,
            'is_delete'=>1,
        ];
        $order_data=OrderModel::join('api_goods','api_goods.goods_id','=','api_order.goods_id')->where($order_where)->get();
        return $order_data;
    }
}
