<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/19
 * Time: 12:01
 */

namespace app\common\model;
use think\Model;
use think\Validate;

class Order extends Model
{
    public function sav($info){
        $data = [
            'out_trade_no'=>$info['out_trade_no'],
            'user_id'=>session('indexuser','','index')['id'],
            'username'=>session('indexuser','','index')['username'],
            'deal_id'=>$info['id'],
            'deal_count'=>$info['deal_count'],
            'total_price'=>$info['total_price'],
            'referer'=>$info['referer'],
            'create_time'=>time(),
            'status' =>1,
        ];
//        var_dump($data);die;
        $validate = new Order;
        $result = $validate->validate(true)->save($data);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $this->getLastInsID();
    }
}