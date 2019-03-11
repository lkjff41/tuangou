<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 20:55
 */

namespace app\common\model;
use think\Model;

class BisAccount extends Model
{
    public function sav($data){
        if ($data['password']!==$data['sepassword']){
            return '';
        }
        $code = mt_rand(100,10000);
        $bisaccount = [
            'username'=>$data['username'],
            'code'=>$code,
            'password'=>md5($data['password'].$code),
            'bis_id'=>$data['bis_id'],
//            'last_login_ip'=>,
//            'last_login_time'=>,
            'is_main'=>$data['is_main'],
            'status'=>$data['status'],
            'create_time'=>time(),
        ];
        $validate = new BisAccount();
        $result = $validate->Validate(true)->save($bisaccount);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $this->getLastInsID();
    }

    public function check($info){
        if(!captcha_check($info['captcha'])){
            return 4;
            //验证码错误
        };
        $havename = $this->where(['username'=>$info['username']])->find();
//        var_dump($havename);die;
        if ($havename&&$havename['status']==1){
            $password = md5($info['password'].$havename['code']);

            if ($havename['password']==$password){
                session('bisAccount',$havename,'bis');
                $this->where('id',$havename['id'])->update(['last_login_time'=>time(),'last_login_ip'=>request()->ip()]);

                return 1;
                //账号密码正确
            }else{
                return 2;///用户存在，密码错误
            }
        }else{
            return 3;
            //用户不存在
        }
    }

    public function dataup($info,$data){
        if ($info['password']!==$info['sepassword']){
            return '';
        }
        if (empty($info['password'])){
            $bisaccount = [
                'id'=>$info['id'],
                'username'=>$info['username'],
                'password'=>$data['password'],
                'update_time'=>time(),
            ];
        }else{
            $bisaccount = [
                'id'=>$info['id'],
                'username'=>$info['username'],
                'password'=>md5($info['password'].$data['code']),
                'update_time'=>time(),
            ];
        }
        $validate = new BisAccount();
        $result = $validate->Validate('BisAccount.edit')->update($bisaccount);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $result['id'];
    }
}