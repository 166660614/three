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
    public function cartJoin(Request $request){
        $goods_id=$request->input('goods_id');
        $goods_num=$request->input('goods_num');
        $user_id=$request->input('user_id');
        //echo $goods_id;
        $where=[
            'goods_id'=>$goods_id,
            'user_id'=>$user_id,
        ];
        $store=GoodsModel::where(['goods_id'=>$goods_id])->value('goods_store');
        if($goods_num>$store){
            $response=[
                'error'=>50001,
                'msg'=>'库存不足'
            ];
            return $response;
        }
        $res=CartModel::where($where)->first();
        //var_dump($res);
        if(empty($res)){
            $data=[
                'goods_id'=>$goods_id,
                'goods_num'=>$goods_num,
                'user_id'=>$user_id,
                'add_time'=>time(),
            ];
            $res1=CartModel::insert($data);
            if($res1){
                $response=[
                    'error'=>0,
                    'msg'=>'ok'
                ];
                return $response;
            }else{
                $response=[
                    'error'=>50001,
                    'msg'=>'添加失败'
                ];
                return $response;
            }
            //var_dump($res1);
        }else{
            $new_num=$res['goods_num']+$goods_num;
            //echo $new_num;exit;
            if($new_num>$store){
                $response=[
                    'error'=>50001,
                    'msg'=>'库存不足'
                ];
                return $response;
            }else{
                $updateWhere=[
                    'goods_num'=>$new_num,
                    'add_time'=>time()
                ];
                $res2=CartModel::where($where)->update($updateWhere);
                if($res2){
                    $response=[
                        'error'=>0,
                        'msg'=>'ok'
                    ];
                    return $response;
                }else{
                    $response=[
                        'error'=>50001,
                        'msg'=>'添加失败'
                    ];
                    return $response;
                }
            }
        }
    }
}
