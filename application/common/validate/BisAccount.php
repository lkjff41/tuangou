<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 11:32
 */
namespace app\common\validate;
use think\Validate;

class BisAccount extends Validate{
    protected $rule = [
        'username'=>'require|max:50|unique:BisAccount',
        'password'=>'require|max:32',
        'code'=>'number',
        'bis_id'=>'require|number',
        'last_login_ip'=>'max:20',
        'last_login_time'=>'number',
        'is_main'=>'number',
        'status'=>'number',
    ];

    protected $message = [

    ];

    protected $scene = [
        'edit'=>['username'=>'require|max:50','password']
    ];
}