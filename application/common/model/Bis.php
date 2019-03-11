<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 20:54
 */

namespace app\common\model;
use think\Model;
use think\Validate;

class Bis extends Model
{
    public function sav($data){
        $bis = [
            'name'=>htmlentities($data['name']),
            'email'=>$data['email'],
            'logo'=>$data['logo'],
            'licence_logo'=>$data['licence_logo'],
            'desc'=>empty($data['desc'])?'':$data['desc'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'bank_info'=>$data['bank_info'],
            'bank_name'=>$data['bank_name'],
            'bank_user'=>$data['bank_user'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
            'status'=>$data['status'],
            'sort'=>50,
            'create_time'=>time(),
        ];

         $validate = new Bis();
         $result = $validate->validate(true)->save($bis);
         if (false===$result){
             dump($validate->getError());die;
         }

        return $this->getLastInsID();
    }


    public function edit($data){
        $bis = [
            'id'=>$data['bisid'],
            'name'=>htmlentities($data['name']),
            'email'=>$data['email'],
            'logo'=>$data['logo'],
            'licence_logo'=>$data['licence_logo'],
            'desc'=>empty($data['desc'])?'':$data['desc'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'bank_info'=>$data['bank_info'],
            'bank_name'=>$data['bank_name'],
            'bank_user'=>$data['bank_user'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
//            'status'=>$data['status'],
//            'sort'=>50,
            'update_time'=>time(),
        ];

        $validate = new Bis();
        $result = $validate->validate('Bis.edit')->update($bis);
        if (false===$result){
            dump($validate->getError());die;
        }else{
            return $result['id'];
        }
    }


    //获取商户列表
    public function getbislist(){
        return $this->where(['status'=>1])->order('id asc')->paginate(15);
    }
    //获取申请入驻商户信息
    public function getbisadd(){
        return $this->where(['status'=>0])->order('id asc')->paginate(15);
    }
    //获取已被删除的商户列表信息
    public function getbisdel(){
        return $this->where(['status'=>2])->order('id asc')->paginate(15);
    }
}
