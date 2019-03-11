<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 21:32
 */

namespace app\common\model;
use think\Model;
use think\Validate;
class IndexUser extends Model
{

    public function sav($info){
        if(empty($info['captcha'])){
            exception('请输入验证码');
            //验证码错误
        }elseif (!captcha_check($info['captcha'])){
            exception('验证码错误');
        }
        if ($info['password']!==$info['sepassword']){
            exception('两次输入密码不一致');
        }elseif(empty($info['password'])||empty($info['sepassword'])){
            exception('请输入密码');
        }
        $code = mt_rand('100','10000');
        $user = [
            'username'=>$info['username'],
            'code'=>$code,
            'password'=>md5($info['password'].$code),
            'email'=>$info['email'],
            'mobile'=>$info['mobile'],
            'create_time'=>time(),
            'status'=>1,
        ];

        $validate = new IndexUser();
        $result = $validate->validate(true)->save($user);
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

        if (empty($info['username'])||empty($info['password'])){
            return 5;
        }
        $havename = $this->whereOr('username|email|mobile','eq',$info['username'])->find();
        if ($havename&&$havename['status']==1){
            $password = md5($info['password'].$havename['code']);
//            var_dump($password);die;
            if ($havename['password']==$password){
                session('indexuser',$havename,'index');
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

    public function dataup($info){
        if ($info['username']==='admin'){
            return false;
        }
        $user = [
            'id'=>$info['id'],
            'username'=>$info['username'],
            'password'=>$info['password'],
            'mobile'=>$info['mobile'],
            'email'=>$info['email'],
            'update_time' => time(),
        ];
        $validate = new IndexUser();
        $result = $validate->validate('IndexUser.edit')->update($user);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $result['id'];
    }

    public function sou($info){
        if (!empty($info['start'])&&!empty($info['stop'])&&
        strtotime($info['stop'])>strtotime($info['start'])){
            $data['create_time'] = [
                ['gt',strtotime($info['start'])],
                ['lt',strtotime($info['stop'])],
            ];
        }elseif (!empty($info['start'])&&empty($info['stop'])){
            $data['create_time'] = ['gt',strtotime($info['start'])];
        }elseif(!empty($info['stop'])&&empty($info['start'])){
            $data['create_time'] = ['lt',strtotime($info['stop'])];
        }

        if (!empty($info['keyword'])){
            $data['username'] = ['like','%'.$info['keyword'].'%'];

//            $data['mobile'] = ['like','%'.$info['keyword'].'%'];
//            $data['email'] = ['like','%'.$info['keyword'].'%'];
        }

        if (!empty($data)){
            return $data;
        }else{
            return '';
        }

    }
}