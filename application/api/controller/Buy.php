<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 13:40
 */
namespace app\api\controller;
use think\Controller;


class Buy extends Controller{
    public function getCount(){
        if (request()->isPost()){
            $count = input('count');
            return show(1,'成功',$count);
        }else{
            return show(0,'失败','');
        }
    }
}