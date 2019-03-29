<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function friendAdd(){
        $user_id=$_POST['user_id'];
        $friend=$_POST['friend_id'];

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
