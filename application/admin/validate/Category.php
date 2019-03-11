<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 13:23
 */
namespace app\admin\validate;
use think\Validate;

class Category extends Validate{
    protected $rule = [
       'id'=>'number',
        'name'=>'require|max:50|unique:category',
        'pid'=>'number',
        'sort'=>'number',
        'status'=>'number|in:-1,0,1',
    ];

    protected $message = [
      'name.require'=>'名不能为空',
      'name.max'=>'不能超50个字符',
      'sort'=>'number',

    ];

    protected $scene = [
        'add'=>['name','pid','id'],
        'edit'=>['name'=>'require|max:50'],
        'status'=>['id','status'],
        'sort'=>['id','sort'],
    ];
}