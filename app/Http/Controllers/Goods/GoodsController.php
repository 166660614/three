<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Model\GoodsModel;
class GoodsController extends Controller
{
    //
    public function goods(){
        $data=GoodsModel::get();
        return $data;
    }
    public function details(Request $request){
        $goods_id=$request->input('goods_id');
        if(empty($goods_id || $goods_id<=0)){
            $response=[
                'error'=>4001,
                'msg'=>'请选择商品'
            ];
        }else{
            $where=[
                'goods_id'=>$goods_id
            ];
            $res=GoodsModel::where($where)->first();
            if(!$res){
                $response=[
                    'error'=>4001,
                    'msg'=>'商品不存在'
                ];
            }else{
                $response=[
                    'error'=>0,
                    'msg'=>$res
                ];
            }
        }
        return $response;
    }
}
