<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 22:33
 */

namespace app\common\Validate;
use think\Validate;

class User extends Validate
{
    protected $rule=[
        'username'=>'require|max:20|unique:User',
        'password'=>'require|max:32',
        'code'=>'require|number',
        'last_login_ip'=>'ip',
        'email'=>'require|email',
        'mobile'=>'require|number',
        'is_main'=>'number',
        'status'=>'require|number',
        'create_time'=>'require|number',
    ];

    protected $message=[

    ];


    protected $scene = [
        'edit'=>[ 'username'=>'require|max:20',
            'password','last_login_ip','code',
            'email','mobile','is_main','status','create_time']
    ];
}