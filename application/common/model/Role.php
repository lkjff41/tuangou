<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/25
 * Time: 22:58
 */

namespace app\common\model;


use think\Model;

class Role extends Model
{
    protected static function init()
    {
        Role::event('after_update', function ($info) {
            if ($info['status']==2){
                db('rolePermission')->where(['role_id' => $info['id']])->delete();
            }
        });
    }

    public function sav($info){
        $data = [
            'name'=>$info['name'],
            'status'=>$info['status'],
        ];

        $validate = new Role();
        $result = $validate->validate(true)->save($data);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $this->getLastInsID();
    }

    public function up($info){
        $data = [
            'id'=>$info['id'],
            'name'=>$info['name'],
            'status'=>$info['status'],
        ];
        $validate = new Role();
        $result = $validate->validate('Role.edit')->update($data);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $result['id'];
    }
}