<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17
 * Time: 22:11
 */

namespace app\index\controller;
use app\index\controller\Base;

class Order extends Base
{
    public function index(){

        if ($this->isLogin()){
            $this->error('请登录','user/login');
        }
        $info = input();
        $info['id']=input('id',0,'intval');
        $info['deal_count'] = input('deal_count',0,'intval');
        $info['total_price'] = input('total_price',0);
        $info['out_trade_no'] = setOrderSn();
        if (!$info['id']){
            $this->error('参数不合法');
        }
        $deal = db('deal')->find($info['id']);
        if (!$deal||$deal['status']!=1){
            $this->error('商品不存在');
        }
        if(empty($_SERVER['HTTP_REFERER'])){
            $this->error('请求不合法');
        }else{
            $info['referer'] = $_SERVER['HTTP_REFERER'];
        }
        try{
            $up = model('order')->sav($info);
        }catch (\Exception $e){
            $this->error('订单处理失败');
        }
        $this->redirect('pay/index',['id'=>$up]);

    }

    public function confirm(){
//        if (!$this->isLogin()){
//            $this->error('请登录','user/login');
//        }
        $this->isLogin();
//        $c = strpos(input('id'),'=');
//        $count = substr(input('id'),$c+1);

        $id=input('id',0,'intval');
        $count = input('count',0,'intval');
        if (!$id){
            $this->error('参数不合法');
        }
        $deal = db('deal')->find($id);
        if (!$deal||$deal['status']!=1){
            $this->error('商品不存在');
        }

        $this->assign([
            'deal'=>$deal,
            'count'=>$count,
        ]);
        return $this->fetch('',[
            'title'=>'支付订单',
            'controller'=>'pay',
        ]);
    }
}