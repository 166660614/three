<?php

namespace App\Http\Controllers\Id;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\IdCardModel;
class IdCard extends Controller
{
    public function apply(){
        return view('api.apply');
    }
    //申请app_secret接口
    public function applyDo(Request $request){
        $api_name=$request->input('api_name');
        $api_idcard=$request->input('api_idcard');
        $api_picture=$request->file('api_picture')->store('images');
        $api_use=$request->input('api_use');
        $info=[
            'apply_name'=>$api_name,
            'apply_idcard'=>$api_idcard,
            'apply_use'=>$api_use,
            'apply_picture'=>$api_picture,
        ];
        $res=IdCardModel::insert($info);
        if($res){
            $data=[
                'result'=>0,
                'msg'=>'申请成功，等待审核'
            ];
        }else{
            $data=[
                'result'=>5001,
                'msg'=>'申请失败，请再次审核'
            ];
        }
        return $data;
//        print_r($api_name.''.$api_idcard.''.$api_picture.''.$api_use);
    }
    public function admin(){
        $apply_info=IdCardModel::get();
        $data=[
            'apply_info'=>$apply_info
        ];
        return view('api.view',$data);
    }
    //审核通过
    public function pass(Request $request){
        $apply_id=$request->route('apply_id');
        $res=IdCardModel::where(['apply_id'=>$apply_id])->update(['apply_status'=>1]);
        if($res){
            //通过存入redis
            $appid_key='appkey:applyid:'.$apply_id;
//            $app_secret=random(15);
//            $app_key=random(15);
            $app_data=[
                'app_key'=>$appid_key,
                'app_secret'=>$app_secret
            ];
            Redis::add($appid_key,$app_data);//将秘钥存入Redis;
            $data=[
                'result'=>0,
                'msg'=>'通过成功',
            ];
        }else{
            $data=[
                'result'=>5001,
                'msg'=>'通过失败'
            ];
        }
        return $data;
    }
    public function refuse(Request $request){
        $apply_id=$request->route('apply_id');
        $res=IdCardModel::where(['apply_id'=>$apply_id])->update(['apply_status'=>2]);
        if($res){
            $data=[
                'result'=>0,
                'msg'=>'驳回成功'
            ];
        }else{
            $data=[
                'result'=>5001,
                'msg'=>'驳回失败'
            ];
        }
        return $data;
    }
    //待审核的申请
    public function waitApply(){
        $info=IdCardModel::where(['apply_status'=>0])->get();
        $data=[
            'data'=>$info,
            'result'=>0
        ];
        return $data;
    }
}
