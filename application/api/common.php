<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 14:02
 */
function show($status,$message='',$date=[]){
    return [
        'status'=>intval($status),
        'message'=>$message,
        'data'=>$date,
    ];
}