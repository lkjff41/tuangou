<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 11:32
 */
namespace app\common\validate;
use think\Validate;

class BisLocation extends Validate{
    protected $rule = [
        'name'=>'require|max:50|unique:BisLocation',
        'logo'=>'max:255',
        'address'=>'require|max:255',
        'tel'=>'number|max:20',
        'contact'=>'max:20',
        'xpoint'=>'max:20',
        'ypoint'=>'max:20',
        'bis_id'=>'max:30',
        'open_time'=>'number|max:10',
        'is_main'=>'number|in:0,1',
        'api_address'=>'max:255',
        'city_id'=>'max:30',
        'city_path'=>'max:50',
        'category_id'=>'max:30',
        'category_path'=>'max:50',
        'bank_info'=>'max:255',
        'faren'=>'max:20',
        'faren_tel'=>'number|max:20',
        'sort'=>'number',
        'status'=>'number',
    ];

    protected $message = [

    ];

    protected $scene = [
        'edit'=>[ 'name'=>'require|max:50','category_id','se_category_id'=>'max:200',
            'logo','address'=>'max:255','contact','xpoint','ypoint',
            'open_time','api_address','city_id','city_path',
            'bank_info','tel']
    ];
}