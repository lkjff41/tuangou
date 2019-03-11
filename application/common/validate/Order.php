<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 11:32
 */
namespace app\common\validate;
use think\Validate;

class Order extends Validate{
    protected $rule = [
        'out_trade_no'=>'require|max:100|unique:Order',
        'transaction_id'=>'max:100',
        'user_id'=>'require|number',
        'username'=>'require|max:50',
        'pay_time'=>'max:20',
        'payment_id'=>'max:1',
        'deal_id'=>'number',
        'deal_count'=>'number',
        'pay_status'=>'number',
        'total_price'=>'require|number',
        'pay_amount'=>'number',
        'referer'=>'max:255',
        'status'=>'number',
    ];

    protected $message = [

    ];
}