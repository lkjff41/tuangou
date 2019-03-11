<?php
namespace app\index\controller;
use app\index\controller\Base;
class Index extends Base{
    public function index()
    {

        $left = $this->bigImage();
        $right = $this->smallImage();
//        $meishi = model('deal')->getIndexShop(1,$this->city->id);
//        var_dump($meishi);die;
        $this->assign('left',$left);
        $this->assign('right',$right);
//        $this->assign('meishi',$meishi);
        return $this->fetch('',[
            'title'=>'886团购网',
        ]);
    }


    //主页大图
    public function bigImage(){
        $left = db('featured')->where(['status'=>['eq',1],'type'=>1])->order('sort desc')->limit(5)->select();
        return $left;
    }


    //主页右侧广告
    public function smallImage(){
        $right = db('featured')->where(['status'=>['eq',1],'type'=>2])->order('sort desc')->limit(1)->select();
        return $right;
    }
}
