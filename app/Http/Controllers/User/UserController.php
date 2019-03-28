<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Model\UserModel;
class UserController extends Controller{
    public function login(){
        $user_account=$_POST['user_name'];
        $user_pwd=$_POST['user_pwd'];
        if(empty($user_account)){
            $res_data=[
                'errcode'=>'5010',
                'errmsg'=>'账号不能为空'
            ];
            return $res_data;
        }
        if(empty($user_pwd)){
            $res_data=[
                'errcode'=>'5010',
                'errmsg'=>'密码不能为空'
            ];
            return $res_data;
        }
        $user_where=[
            'user_account'=>$user_account,
            'user_pwd'=>$user_pwd
        ];
        $user_data=UserModel::where($user_where)->first();
        $ktoken='token:u:'.$user_data['user_id'];
        $token=$token=str_random(32);
        Redis::hSet($ktoken,'app:token',$token);
        Redis::expire($ktoken,60*5);
        if($user_data){
            $res_data=[
                'errcode'=>'4001',
                'errmsg'=>'登陆成功',
                'token'=>$token,
                'user_id'=>$user_data['user_id'],
                'user_name'=>$user_data['user_name'],
            ];
            return $res_data;
        }else{
            $res_data=[
                'errcode'=>'5011',
                'errmsg'=>'账号或者密码错误'
            ];
            return $res_data;
        }
    }
    public function register(){
        $uname=$_POST['uname'];
        $upwd=$_POST['upwd'];
        $uemail=$_POST['uemail'];
        $info=[
            'user_name'=>$uname,
            'user_pwd'=>$upwd,
            'user_email'=>$uemail
        ];
        $res=UserModel::insert($info);
        if($res){
            $data=[
                'errcode'=>'4001',
                'errmsg'=>'注册成功'
            ];
        }else{
            $data=[
                'errcode'=>'5001',
                'errmsg'=>'注册失败'
            ];
        }
        return $data;
    }
    public function center(){
        $user_id=$_POST['user_id'];
        $token=$_POST['token'];
        $ktoken='token:u:'.$user_id;
        $redis_token=Redis::hget($ktoken,'app:token');
        if($token==$redis_token){
            $user_info=UserModel::where(['user_id'=>$user_id])->first();
            $data=[
                'errcode'=>'4001',
                'errmsg'=>'ok',
                'user_name'=>$user_info['user_name'],
            ];
        }else{
            $data=[
                'errcode'=>'5001',
                'errmsg'=>'no'
            ];
        }
        return $data;
    }
}