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
                'errcode'=>0,
                'msg'=>'登陆成功',
                'token'=>$token,
                'user_id'=>$user_data['user_id'],
                'user_name'=>$user_data['user_name'],
            ];
        }else{
            $res_data=[
                'errcode'=>'5011',
                'msg'=>'账号或者密码错误'
            ];
        }
        return $res_data;
    }
    public function register(){
        $uname=$_POST['uname'];
        if(empty($uname)){
            $data=[
                'errcode'=>6001,
                'msg'=>'用户名不能为空'
            ];
        }
        $upwd=$_POST['upwd'];
        if(empty($upwd)){
            $data=[
                'errcode'=>6001,
                'msg'=>'密码不能为空'
            ];
        }
        $upwd2=$_POST['upwd2'];
        if($upwd2!=$upwd){
            $data=[
                'errcode'=>6001,
                'msg'=>'密码和确认密码不一致'
            ];
        }
        $uemail=$_POST['uemail'];
        if(empty($uemail)){
            $data=[
                'errcode'=>6001,
                'msg'=>'邮箱不能为空'
            ];
        }
        $info=[
            'user_name'=>$uname,
            'user_pwd'=>$upwd,
            'user_account'=>$uname,
            'user_email'=>$uemail,
        ];
        $res=UserModel::insert($info);
        if($res){
            $data=[
                'errcode'=>0,
                'msg'=>'注册成功'
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'msg'=>'注册失败'
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
                'errcode'=>0,
                'msg'=>'ok',
                'user_name'=>$user_info['user_name'],
            ];
        }else{
            $data=[
                'errcode'=>5001,
                'msg'=>'no'
            ];
        }
        return $data;
    }
}