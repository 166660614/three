<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\FriendModel;
use App\Model\UserFriendModel;
class FriendController extends Controller
{
    public function friendAdd(){
        $user_id=$_POST['user_id'];
        $friend_id=$_POST['friend_id'];
        $user_name=$_POST['user_name'];
        $data_friend=[
            'friend_id'=>$friend_id,
            'friend_name'=>$user_name,
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
        $user_name=$_POST['user_name'];
        $user_account=$_POST['user_account'];
        $user_where=[
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'user_account'=>$user_account
        ];
        $user_data=UserModel::where($user_where)->get();
        return $user_data;
    }
}
