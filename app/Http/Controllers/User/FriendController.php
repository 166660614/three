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
}
