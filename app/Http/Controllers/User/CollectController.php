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
                'errmsg'=>'收藏成功'
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'errmsg'=>'您已经收藏过了'
            ];
        }
        return $data;
    }
    public function collectZrange(Request $request){
        $user_id=$request->input('user_id');
        if(empty($user_id)){
            $data=[
                'errcode'=>4001,
                'errmsg'=>'请先登录'
            ];
            return $data;
        }
        $coll_key='test:'.$user_id;
        $res=Redis::zRange($coll_key,0,1,true);
        if(empty($res)){
            $data=[
                'errcode'=>4002,
                'errmsg'=>'你还没有收藏'
            ];
            return $data;
        }else{
            $arr=[];
            foreach ($res as $k=>$v){
                //echo $k;
                $res1=GoodsModel::where(['goods_id'=>$k])->first();
                if(empty($res1)){
                    $data=[
                        'errcode'=>4002,
                        'errmsg'=>'商品已不存在'
                    ];
                    return $data;
                }
                //var_dump($res1);
                $arr[]=$res1;
                //var_dump($arr);
            }
            $data=[
                'errcode'=>0,
                'errmsg'=>$arr
            ];
            return $data;
        }
        //return $data;
    }
}
