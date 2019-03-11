<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 22:16
 */

namespace app\admin\controller;
//use app\bis\controller\Base;
use think\Controller;
use think\captcha;
use app\common\model\User;

class Login extends Controller
{
    public function index(){
        $userm = new User();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $havename = model('user')->where(['username'=>$info['username']])->find();
//            if (!$havename['is_main']==1){
//                $this->error('权限不足，无法登陆');
//            }
            $ch = $userm->check($info);

            if ($ch==4){
                $this->error('验证码错误');
            }elseif($ch==2||$ch==3){
                $this->error('账号或用户名错误,或者未通过审核');
            }elseif ($ch==1){
                $this->success('登录成功','index/index');
            }

        }else{
            return $this->fetch();
        }
    }
}