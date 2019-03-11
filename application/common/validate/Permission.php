<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 22:33
 */

namespace app\common\Validate;
use think\Validate;

class Permission extends Validate
{
    protected $rule=[
        'name'=>'require|max:50|unique:Permission',
        'model'=>'max:50',
        'controller'=>'max:50',
        'action'=>'max:50',
        'pid'=>'number',
    ];

    protected $message=[

    ];

    protected $scene=[
      'edit'=>['name'=>'require|max:50','model','controller','action','pid']
    ];
}