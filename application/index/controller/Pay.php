<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/19
 * Time: 13:48
 */

namespace app\index\controller;
use think\Controller;

class Pay extends Base
{
    public function index($id){
        $this->isLogin();
        if (empty($id)){
            $this->error('请求不合法');
        }
        $order = db('order')->find($id);
        if (!$order||$order['status']!=1||$order['pay_status']!=0){
            $this->error('无法进行该操作');
        }
        if ($order['user_id']!=session('indexuser','','index')['id']){
            $this->error('系统错误');
        }
        $deal = db('deal')->find($order['deal_id']);
        if (request()->isPost()){
            $info = input('post.');
            $data = [
              'id'=>$id,
              'pay_status'=>$info['pay_status']
            ];
//            var_dump($info);die;
            $up = model('order')->update($data);
            if ($up){
//                $a = $deal['total_count']-$order['deal_count'];
//                var_dump($a);die;
//                $a = model('deal')->where('id',$order['deal_id'])->setInc('buy_count',$order['deal_count']);
                $b = db('deal')->where('id',$order['deal_id'])->update(['total_count'=>$deal['total_count']-$order['deal_count'],'buy_count'=>$deal['buy_count']+$order['deal_count']]);
                if ($b){
                    $this->redirect('pay/ok');
                }
            }
        }
        $this->assign([
            'deal'=>$deal,
            'order'=>$order,
            ]);
        return $this->fetch('',[
            'title'=>'支付信息',
            ]);
    }

    public function ok(){
        return $this->fetch('pay/success',[
            'title'=>'支付信息'
        ]);
    }
}