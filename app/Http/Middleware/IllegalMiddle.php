<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;
use Closure;
class IllegalMiddle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, Closure $next)
//    {
//        $request_url=substr(md5($_SERVER['REQUEST_URI']),0,10);
//        $invalid_ip=$_SERVER['REMOTE_ADDR'];
//        $redis_key="str:".$request_url.'ip:'.$invalid_ip;
//        $count=Redis::incr($redis_key);
//        $invalid_time=Redis::expire($redis_key,20);
//        if($count>2 && $invalid_time<=20){
//            //防止恶意刷api
//            $data=[
//                'errcode'=>5005,
//                'errmsg'=>'many request'
//            ];
//            return json_encode($data);
//        }
//        return $next($request);
//    }
    private $_post_data=[];
    private $_decrypt_data=[];
    private $_api_key='';
    private $_blank_list='blank_list';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->_post_data=$request->all();
        if(!empty($this->_post_data)){
            //将传过来的数据解密
            $this->_decrypt();
            //防止接口被刷
            $count=$this->_protectApi();
            if($count['code']!=1000){
                $data=[
                    'code'=>5004,
                    'msg'=>'接口调用频繁'
                ];
                return response($data);
            }
            //验签
            $result=$this->_checkSign();
            //判断验签是否成功
            if($result['code']!=1000){
                $res=[
                    'msg'=>$result['msg'],
                    'code'=>$result['code']
                ];
                return response($res);
            }
                $request->request->replace($this->_decrypt_data);
                $response=$next($request);
                $data_arr=[];
                $data=$response->original;
                $data_arr['data']=openssl_encrypt(json_encode($data),'AES-256-CBC','returndata',false,'1234567890123456');
                ksort($data);
                //加签名
                $data2=[
                    md5('yueluchuan')=>md5('123456')
                ];
                $sign_data=md5(http_build_query($data).'&api_sercet='.$data2[$this->_api_key]);
                $data_arr['sign']=$sign_data;
                return response($data_arr);
        }
    }

    private function _protectApi(){
        $_api_key=$this->_api_key;
        $blank_list=$this->_blank_list;
        //判断是否在黑名单
        $bool_blank=Redis::zScore($blank_list,$_api_key);
        if($bool_blank==false){
            //不在黑名单 记录ip调用次数 加入黑名单
            $res=$this->_addApiCount();
            return $res;
        }else{
            //在黑名单 查询调用次数和时间
            $res=$this->_addApiBlank();
            return $res;
        }
    }

    /**
     * app_key不在黑名单
     */
    private function _addApiCount(){
        //记录ip调用次数
        $count=Redis::incr($this->_api_key);
        if($count==1){
            Redis::Expire($this->_api_key,10);
        }
        if($count>=20){
            Redis::zAdd($this->_blank_list,time(),$this->_api_key);
            Redis::del($this->_api_key);
            $result=[
                'msg'=>'调用次数频繁',
                'code'=>5004,
            ];
            return $result;
        }else{
            return [
                'code'=>1000,
                'msg'=>'接口正常',
            ];
        }
    }

    /**
     * app_key在黑名单
     */
    private function _addApiBlank(){
        //判断调用次数是否过期
        $expire_time=Redis::zScore($this->_blank_list,$this->_api_key);
        if(time()-$expire_time>=10){
            Redis::zRemove($this->_blank_list,$this->_api_key);
            $this->_addApiCount();
        }else{
            $result=[
                'msg'=>'调用次数频繁',
                'code'=>5004,
            ];
            return $result;
        }
    }
    /**
     * @return $decrypt_data 解密传输过来的数据
     */
    private function _decrypt(){
            $decrypt_data=json_decode(openssl_decrypt($this->_post_data['data'],'AES-256-CBC','string',false,'1234567890123456'),true);
            $this->_api_key=$decrypt_data['api_key'];
            $this->_decrypt_data=$decrypt_data;
    }

    /**
     * @return result 验签结果
     */
    private function _checkSign(){
        $data=[
            md5('yueluchuan')=>md5('123456')
        ];
        if(!array_key_exists($this->_api_key,$data)){
            $res=[
                'result'=>5005,
                'msg'=>'check sign fail',
                'data'=>[],
            ];
            return $res;
        }
        ksort($this->_decrypt_data);
        $server_str=md5(http_build_query($this->_decrypt_data).'&api_sercet='.$data[$this->_api_key]);
        if($this->_post_data['sign']==$server_str){
            $res=[
                'code'=>1000,
                'msg'=>'check sign successly'
            ];
            return $res;
        }else{
            $res=[
                'code'=>5002,
                'msg'=>'验签失败'
            ];
            return $res;
        }
    }
}
