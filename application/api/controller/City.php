<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 13:40
 */
namespace app\api\controller;
use think\Controller;
use app\common\model\City as cityModel;

class City extends Controller{
    public function getCityByParentId(){
        $citym = new cityModel();
        if (request()->isPost()){
            $id = input('id');
            $data = $citym->getchilrenid($id);
            return show(1,'成功',$data);
        }else{
            return show(0,'失败','');
        }
    }
}