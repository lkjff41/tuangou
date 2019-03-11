<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/1
 * Time: 16:10
 */

namespace app\index\controller;
use app\index\controller\Base;


class Myorder extends Base
{
    public function index(){
        $this->isLogin();
        $usersession = session('indexuser','','index');
        $deal = model('order')
            ->alias('a')
            ->join('deal b','a.deal_id=b.id')
            ->field('a.*,b.name,b.image')
            ->where(['a.user_id'=>$usersession['id'],'a.status'=>['neq',2]])->paginate(10);

//        var_dump($deal);die;
        $this->assign('deal',$deal);
        return $this->fetch('',[
            'title'=>'我的订单'
        ]);
    }

    public function cancel(){
        if (request()->isGet()){
            $info = input();
            $data = [
                'id'=>$info['id'],
                'status'=>$info['status']
            ];
//            var_dump($info);die;
            $up = model('order')->update($data);
            if ($up){
                $this->redirect('myorder/index');
            }
        }
    }
}