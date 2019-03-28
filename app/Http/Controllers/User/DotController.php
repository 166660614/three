<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class DotController extends Controller
{
    public function dot(){
        $timestamps=$_POST['timestamps'];
        $user_id=$_POST['user_id'];
        $coll_key='dot:user:'.$user_id;
        $res=Redis::zAdd($coll_key,$timestamps);
        if($res){
            $data=[
                'errcode'=>4001,
                'errmsg'=>'点赞成功'
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'errmsg'=>'您已经赞过了'
            ];
        }
        return $data;
    }
}
