<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/19
 * Time: 14:48
 */

namespace app\index\controller;
use think\Controller;

class Weixinpay extends Controller
{
    public function notify(){
        $weixinData = file_get_contents('php://input');
        file_put_contents('/tmp/2.txt',$weixinData,FILE_APPEND);
    }
}