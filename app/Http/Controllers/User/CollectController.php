<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class CollectController extends Controller
{
    public function collectAdd(){
        $timestamps=$_POST['timestamps'];
        $user_id=$_POST['user_id'];
        $goods_id=$_POST['goods_id'];
        $coll_key='collecion:user:'.$user_id;
        $res=Redis::zAdd($coll_key,$timestamps,$goods_id);
        if($res){
            $data=[
                'errcode'=>4001,
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
        $coll_key='collecion:user:'.$user_id;
        $res=Redis::zRange($coll_key,0,1,true);
        if(empty($res)){
            $data=[
                'errcode'=>4001,
                'errmsg'=>'你还没有收藏'
            ];
        }else{
            $data=[
                'errcode'=>0,
                'errmsg'=>$res
            ];
        }
        return $data;
    }
}
