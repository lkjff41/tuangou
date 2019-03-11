<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/6
 * Time: 22:42
 */

namespace app\common\model;
use think\Model;

class Featured extends Model
{
    public function gettype($type){
        if ($type!=0){
            $data['type'] = ['eq',$type];
            $data['status'] = ['neq',-1];
        }else{
            $data['status'] = ['neq',-1];
        }
        $data = $this->where($data)->order('sort desc')->select();
        return $data;
    }

    public function sav($info){
        $data = [
            'type'=>$info['type'],
            'title'=>$info['title'],
            'image'=>$info['image'],
            'url'=>$info['url'],
            'desc'=>$info['desc'],
            'sort'=>50,
            'create_time'=>time(),
        ];

        $validate = new Featured();
        $result = $validate->validate(true)->save($data);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $this->getLastInsID();
    }

    public function up($data){
        $validate = new Featured();
        $result = $validate->validate('Featurde.edit')->update($data);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $this->getLastInsID();
    }
}