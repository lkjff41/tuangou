<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 11:32
 */
namespace app\common\validate;
use think\Validate;

class Bis extends Validate{
    protected $rule = [
        'name'=>'require|max:50|unique:Bis',
        'email'=>'require|email',
        'logo'=>'max:255',
        'licence_logo'=>'max:255',
        'city_id'=>'require|number',
        'city_path'=>'require|max:50',
        'bank_info'=>'require|max:255',
        'bank_name'=>'require|max:50',
        'bank_user'=>'require|max:50',
        'faren'=>'max:20',
        'faren_tel'=>'number|max:20',
        'sort'=>'number',
        'status'=>'number',
    ];

    protected $message = [

    ];

    protected $scene = [
        'edit'=>[ 'name'=>'require|max:50','email','logo',
            'licence_logo','faren','faren_tel','city_id',
            'city_path','bank_info','bank_name','bank_user']
    ];
}