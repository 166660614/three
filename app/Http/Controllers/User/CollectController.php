<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Model\GoodsModel;

class CollectController extends Controller
{
    public function collectAdd(){
        $timestamps=$_POST['timestamps'];
        $user_id=$_POST['user_id'];
        if(empty($user_id)){
            $data=[
                'errcode'=>4001,
                'msg'=>'请先登录'
            ];
            return $data;
        }
        $goods_id=$_POST['goods_id'];
        $coll_key='collecion:user:'.$user_id;
        $res=Redis::zAdd($coll_key,$timestamps,$goods_id);
        if($res){
            $data=[
                'errcode'=>0,
                'msg'=>'收藏成功'
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'msg'=>'您已经收藏过了'
            ];
        }
        return $data;
    }
    public function collectZrange(Request $request){
        $user_id=$request->input('user_id');
        if(empty($user_id)){
            $data=[
                'errcode'=>4001,
                'msg'=>'请先登录'
            ];
            return $data;
        }
        $coll_key='collecion:user:'.$user_id;
        $res=Redis::zRange($coll_key,0,-1,true);
        if(empty($res)){
            $data=[
                'errcode'=>4002,
                'msg'=>'你还没有收藏'
            ];
            return $data;
        }else{
            $arr=[];
            foreach ($res as $k=>$v) {
                //echo $k;
                $res1 = GoodsModel::where(['goods_id' => $k])->first();
                $res1['add_time'] = $v;
                if (empty($res1)) {
                    $data = [
                        'errcode' => 4002,
                        'msg' => '商品已不存在'
                    ];
                    return $data;
                }
                $arr[] = $res1;
            }
            return $arr;
        }
    }
}
