<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 11:32
 */
namespace app\common\validate;
use think\Validate;

class Deal extends Validate{
    protected $rule = [
        'name'=>'require|max:100',
        'category_id'=>'require|number',
        'se_category_id'=>'require|max:200',
        'bis_id'=>'require|number',
        'location_ids'=>'require|max:100',
        'image'=>'max:200',
//        'desc'=>'require|max:50',
        'start_time'=>'number',
        'end_time'=>'number',
        'origin_price'=>'float',
        'current_price'=>'float',
        'city_id'=>'require|number',
        'buy_count'=>'number',
        'total_count'=>'number',
        'coupons_begin_time'=>'number',
        'coupons_end_time'=>'number',
        'xpoint'=>'max:20',
        'ypoint'=>'max:20',
        'bis_account_id'=>'number',
        'balance_price'=>'float',
//        'notes'=>'number',
        'sort'=>'number',
        'status'=>'number',
    ];

    protected $message = [

    ];

    protected $scene = [
        'edit'=>[ 'name','category_id','se_category_id'=>'max:200',
            'origin_price','image','location_ids'=>'require','start_time','end_time','balance_price',
            'current_price','city_id'=>'number','buy_count','total_count',
            'coupons_begin_time','coupons_end_time']
    ];
}