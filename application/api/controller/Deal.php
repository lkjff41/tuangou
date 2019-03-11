<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16
 * Time: 22:21
 */
namespace app\api\controller;
use think\Controller;
use app\common\model\Deal as dealModel;

class Deal extends Controller{
    public function getListShops(){
        $deal = new dealModel();
        if (request()->isPost()) {
            $cate = input('cate');
            $cityid = input('cityid');
            $data = $deal->getListShop($cate,'',$cityid);
            return show(1,'获取成功',$data);
        }else{
            return show(0,'获取失败','');
        }
    }
}
