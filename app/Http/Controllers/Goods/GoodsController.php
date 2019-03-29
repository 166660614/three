<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Model\GoodsModel;
use App\Model\UserModel;
class GoodsController extends Controller
{
    //
    public function goods(){
        $data=GoodsModel::get();
        $arr=[
            'data'=>$data
        ];
        return json_decode($data,true);
    }
    public function details(Request $request){
        $goods_id=$request->input('goods_id');
        $salekey='sale:value:goods:'.$goods_id;
        $salenum=Redis::zscore($salekey,$goods_id);
        if(empty($goods_id || $goods_id<=0)){
            $response=[
                'error'=>4001,
                'msg'=>'请选择商品'
            ];
        }else{
            $where=[
                'goods_id'=>$goods_id
            ];
            $res=GoodsModel::where($where)->first();
            if(!$res){
                $response=[
                    'error'=>4001,
                    'msg'=>'商品不存在'
                ];
            }else{
                $response=[
                    'error'=>0,
                    'msg'=>$res,
                    'salenum'=>$salenum,
                ];
            }
        }
        return $response;
    }
    public function salevalue(){
        $goods_id=$_POST['goods_id'];
        $salekey='sale:value:goods:'.$goods_id;
        $salevalue=Redis::zincrby($salekey,1,$goods_id);//每点击商品一下增加访问量1次
        $data=[
            'errcode'=>0,
            'msg'=>'ok',
        ];
        return $data;
    }
    public function people(Request $request){
        $goods_id=$request->input('goods_id');
        if(empty($goods_id)){
            return [
                'error'=>4003,
                'msg'=>'非法操作'
            ];
        }
        $user_id=$request->input('user_id');
        if(!empty($user_id)){
            $salekey='people:user:'.$goods_id;
            Redis::zAdd($salekey,time(),$user_id);
            $people=Redis::zRange($salekey,0,-1);
            //print_r($people);
            $arr=[];
            foreach ($people as $k=>$v){
                $res=UserModel::where(['user_id'=>$v])->first();
                if(empty($res)){
                    $data=[
                        'error'=>4002,
                        'msg'=>'暂无人浏览'
                    ];
                    return $data;
                }
                $arr[]=$res;
            }
            $data=[
                'error'=>0,
                'msg'=>$arr
            ];
            return  $data;

        }
    }
}
