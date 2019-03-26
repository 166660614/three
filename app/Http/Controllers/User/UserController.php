<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        }else{
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
        if($user_data){
            $res_data=[
                'errcode'=>'4001',
                'errmsg'=>'登陆成功'
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
}