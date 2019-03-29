<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use App\Model\FriendModel;
use App\Model\UserFriendModel;
class FriendController extends Controller
{
    public function friendAdd(){
        $user_id=$_POST['user_id'];
        $friend_id=$_POST['friend_id'];
        $user_name=$_POST['user_name'];
        $friend_account=$_POST['friend_account'];
        $data_friend=[
            'friend_name'=>$user_name,
            'friend_account'=>$friend_account,
        ];
        $res_friend=FriendModel::insert($data_friend);//好友列表添加
        $data_user_friend=[
            'user_id'=>$user_id,
            'friend_id'=>$friend_id,
        ];
        UserFriendModel::insert($data_user_friend);//当前用户添加好友
        $data_friend_user=[
            'user_id'=>$friend_id,
            'friend_id'=>$user_id,
        ];
        $res=UserFriendModel::insert($data_friend_user);//好友自动同意添加
        if($res){
            $data=[
                'errcode'=>0,
                'msg'=>'好友添加成功',
            ];
        }else{
            $data=[
                'errcode'=>6021,
                'msg'=>'好友添加异常',
            ];
        }
    }
    public function addfriend(Request $request){
        $user_id=$_POST['user_id'];
        if(empty($user_id)){
            $data=[
                'errcode'=>4001,
                'msg'=>'您还没登录'
            ];
            return $data;
        }
        $user_data=UserModel::where(['user_id'=>$user_id])->get();
        if($user_data){
            $data=[
                'errcode'=>0,
                'msg'=>$user_data,
            ];
            return $data;
        }
    }
}
