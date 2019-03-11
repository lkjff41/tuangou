<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 11:32
 */
namespace app\common\validate;
use think\Validate;

class Featured extends Validate{
    protected $rule = [
        'type'=>'number',
        'title'=>'require|unique:featured',
        'image'=>'max:255',
        'url'=>'max:255',
        'sort'=>'number',
        'status'=>'number',
    ];

    protected $message = [

    ];

    protected $scene = [
        'edit'=>['title'=>'require','type','image','url']
    ];
}