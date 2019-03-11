<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 22:16
 */

namespace app\bis\controller;
//use app\bis\controller\Base;
use think\Controller;
use think\captcha;
use app\common\model\BisAccount;

class Login extends Controller
{
    public function index(){
        $accountm = new BisAccount();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');

            $ch = $accountm->check($info);
//            var_dump($ch);die;

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