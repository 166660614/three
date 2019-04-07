<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(Request $request){
       $res=
           [
               'code'=>1000,
               'msg'=>'成功',
               'data'=>[]
           ];
       return $res;
    }

}
