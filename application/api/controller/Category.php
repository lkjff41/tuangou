<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 13:40
 */
namespace app\api\controller;
use think\Controller;
use app\common\model\Category as cateModel;

class Category extends Controller{
    public function getCateByParentId(){
        $catem = new cateModel();
        if (request()->isPost()){
            $id = input('id');
            $data = $catem->getchilrenid($id);
            return show(1,'成功',$data);
        }else{
            return show(0,'失败','');
        }
    }
}