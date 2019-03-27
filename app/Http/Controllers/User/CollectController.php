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
        $coll_key='collecion:redis:';
        $res=Redis::zIncrBy($coll_key,$timestamps,$user_id);
        if($res){
            $data=[
                'errcode'=>4001,
                'errmsg'=>'ok'
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'errmsg'=>'no'
            ];
        }
        return $data;
    }
}
