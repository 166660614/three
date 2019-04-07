<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/1 0001
 * Time: 15:54
 */
/**
 * @param $data
 * @param $msg
 * @param $result
 * @return array
 */
function showMsg($data,$msg,$result){
    $array=[
        'data'=>$data,
        'msg'=>$msg,
        'result'=>$result,
    ];
    return $array;
}