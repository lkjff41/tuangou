<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 13:06
 */
namespace app\api\controller;
use app\common\model\BisAccount;
class Register{
    public function maptag($address){
        $lnglat = \Map::getLngLat($address);
//        var_dump($lnglat);die;
        if ($lnglat['status']!=0||$lnglat['result']['confidence']<35){
            return show(0,'定位失败，或者不正确','');
        }else{
            $lnglat = json_encode($lnglat);
            return show(1,'定位成功',$lnglat);
        }
    }

    public function getxy($address){
        $lnglat = \Map::getLngLat($address);
//        var_dump($lnglat);die;
        if ($lnglat['status']!=0||$lnglat['result']['confidence']<35){
            return '';
        }else{
            return $lnglat;
        }
    }

    public function cfname($username){
        $account = new BisAccount();
        $cfname = $account->where(['username'=>$username])->select()->toArray();
        if ($cfname){
            return show(0,'该用户名已存在','');
        }else{
            return show(1,'该用户名可以使用','');
        }
    }

    public function repassword($password,$sepassword){
        if (empty($password)||empty($sepassword)){
            return show(0,'密码不能为空','');
        }
        if ($password===$sepassword){
            return show(1,'密码一致','');
        }else{
            return show(0,'填写的密码不一致！','');
        }
    }

    public function cfusername($username){
        $cfname = model('indexUser')->where(['username'=>$username])->select()->toArray();
//        var_dump($cfname);die;
        if ($cfname){
            return show(0,'该用户名已存在','');
        }else{
            return show(1,'该用户名可以使用','');
        }
    }

    public function cfadminname($username){
        $cfname = model('User')->where(['username'=>$username])->select()->toArray();
        if ($cfname){
            return show(0,'该用户名已存在','');
        }else{
            return show(1,'该用户名可以使用','');
        }
    }

    public function checkcaptcha($captcha){
        if (!captcha_check($captcha)){
            return show(0,'验证码错误','');
        }else{
            return show(1,'验证码正确','');
        }
    }

    public function status($id,$status,$model){
//        if ($id==1){
//            return show(0,'超级管理员不能修改状态','');
//        }
        $up = model($model)->update(['id'=>$id,'status'=>$status]);
        if (!$up){
            return show(0,'修改失败','');
        }else{
            return show(1,'修改成功',$id);
        }
    }

}