<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 22:33
 */

namespace app\common\Validate;
use think\Validate;

class Role extends Validate
{
    protected $rule=[
        'name'=>'require|max:50|unique:Role',
        'sort'=>'max:11',
    ];

    protected $message=[

    ];

    protected $scene=[
      'edit'=>['name'=>'require|max:50|','sort']
    ];
}