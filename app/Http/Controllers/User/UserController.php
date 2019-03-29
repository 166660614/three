<?php
namespace App\Http\Controllers\User;
use function GuzzleHttp\Psr7\str;
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
                'msg'=>'账号不能为空'
            ];
            return $res_data;
        }
        if(empty($user_pwd)){
            $res_data=[
                'errcode'=>'5010',
                'msg'=>'密码不能为空'
            ];
            return $res_data;
        }
        if(is_numeric($user_account) || strlen($user_account)==11){
            $user_where=[
                'user_tel'=>$user_account,
                'user_pwd'=>$user_pwd
            ];
        }elseif(substr_count($user_account,'@')!=0){
            $user_where=[
                'user_email'=>$user_account,
                'user_pwd'=>$user_pwd
            ];
        }else{
            $user_where=[
                'user_name'=>$user_account,
                'user_pwd'=>$user_pwd
            ];
        }

        $user_data=UserModel::where($user_where)->first();
        $ktoken='token:u:'.$user_data['user_id'];
        $token=$token=str_random(32);
        Redis::hSet($ktoken,'app:token',$token);
        Redis::expire($ktoken,3600*24*3);
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
            return $data;
        }elseif (strlen($uname)>10){
            $data=[
                'errcode'=>6001,
                'msg'=>'用户名最多10位'
            ];
            return $data;
        }
        $res_info=UserModel::where(['user_name'=>$uname])->first();
        if($res_info){
            $data=[
                'errcode'=>6001,
                'msg'=>'客观，您输入的账号已被注册！换一个呗。'
            ];
            return $data;
        }
        $upwd=$_POST['upwd'];
        if(empty($upwd)){
            $data=[
                'errcode'=>6001,
                'msg'=>'密码不能为空'
            ];
            return $data;
        }
        $upwd2=$_POST['upwd2'];
        if($upwd2!=$upwd){
            $data=[
                'errcode'=>6001,
                'msg'=>'密码和确认密码不一致'
            ];
            return $data;
        }
        $uemail=$_POST['uemail'];
        if(empty($uemail)){
            $data=[
                'errcode'=>6001,
                'msg'=>'邮箱不能为空'
            ];
            return $data;
        }elseif(substr_count($uemail,'@')==0){
            $data=[
                'errcode'=>6002,
                'msg'=>'邮箱格式不符合'
            ];
            return $data;
        }
        $utel=$_POST['utel'];
        if(empty($utel)){
            $data=[
                'errcode'=>6001,
                'msg'=>'手机号不能为空'
            ];
            return $data;
        }elseif(!is_numeric($utel) || strlen($utel)!=11){
            $data=[
                'errcode'=>6001,
                'msg'=>'手机号格式不符合'
            ];
            return $data;
        }
        $info=[
            'user_name'=>$uname,
            'user_pwd'=>$upwd,
            'user_tel'=>$utel,
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

    //修改密码
    public  function updatePwd()
    {
        $user_id = $_POST['user_id'];
        if (!empty($user_id)) {
            $userinfo = UserModel::where(['user_id' => $user_id])->first();
            $uname = $userinfo['user_account'];
            $data = [
                'errcode' => 0,
                'uname' => $uname
            ];
            return $data;
        }
    }
    public function pwd1(){
        $pwd=$_POST['upwd'];
        $pwd1=$_POST['upwd1'];
        $pwd2=$_POST['upwd2'];
        $user_id=$_POST['user_id'];
        $userinfo=UserModel::where(['user_id'=>$user_id])->first();
        $upwd=$userinfo['user_pwd'];
        if($pwd!=$upwd){
            $data=[
                'errcode' => 50001,
                'msg'     => '原密码错误'
            ]; 
        }else{
            if($pwd1!=$pwd2){
                $data=[
                    'errcode' => 50001,
                    'msg'     => '确认密码需和密码一致'
                ];
            }else{
                UserModel::where(['user_id'=>$user_id])->update(['user_pwd'=>$pwd1]);
                $data=[
                    'errcode'=>0,
                    'msg'=>'ok'
                ];
            }
        }
        return $data;
    }
}