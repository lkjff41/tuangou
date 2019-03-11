<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\controller\Base;
//超级管理员应该可以随意修改所有用户密码
class User extends Base
{
    public function login()
    {
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $up = model('indexUser')->check($info);

            if ($up==4){
                $this->error('验证码错误');
            }elseif ($up==5){
                $this->error('账号或密码未填写');
            } elseif($up==2||$up==3){
                $this->error('账号或用户名错误,或者未通过审核');
            }elseif ($up==1){

                $this->success('登录成功','index/index');
            }
        }else{
            return $this->fetch('',[
                'title'=>'登录'
            ]);
        }

    }

//    function microtime_float()
//    {
//        list($usec, $sec) = explode(" ", microtime());
//        return ((float)$usec + (float)$sec);
//    }

    /**
     * 申请忘记密码
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function forget(){
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            if(!captcha_check($info['captcha'])){
                $this->error('验证码错误');
            }
            $data = model('indexUser')->where(['username'=>$info['username']])->find();
            if ($data['email']==$info['email']){
                $code = md5((microtime(true)*1000)+$info['username']);
                $url = request()->domain().url('index.php/index/user/forgetedit',['code'=>$code,'id'=>$data['id']]);
                $title = '找回密码';
                $content = "点击链接修改密码
                <a href='" . $url . "' target='_blank'>查看链接</a>";
                \phpmailer\Email::send($data['email'],$title,$content);
                Session::set('code',$code);
                Session::set('timeout',time());
                $this->success('邮件已发送成','user/login');
            }else{
                $this->error('邮箱错误');
            }
        }else{
            return $this->fetch('',[
                'title'=>'找回密码'
            ]);
        }
    }


    /**
     * 找回密码
     * @param $id 账号id
     * @param int $code 时间戳验证
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function forgetedit($id,$code=0){
//        var_dump(Session::get('code'));die;
        if (Session::get('code')){
            if (Session::get('code')!=$code){
                $this->error('没有权限进入','user/login');
            }
        }else{
            $this->error('没有权限进入','user/login');
        }
        if (Session::get('timeout')){
            if (Session::get('timeout')+1800<time()){
                session('timeout',null);
                session('code',null);
                $this->error('链接超时','user/login');
            }
        }else{
            $this->error('没有权限进入','user/login');
        }
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $users = model('indexUser')->find($id);
            if ($info['username']!==$users['username']){
                $this->error('账号输入错误');
            }
            if ($info['password']!==$info['sepassword']){
                $this->error('两次输入密码不一致');
            }elseif(empty($info['password'])||empty($info['sepassword'])){
                $this->error('请输入密码');
            }
            $code = mt_rand('100','10000');
            $data = [
                'id'=>$info['id'],
                'code'=>$code,
                'password'=>md5($info['password'].$code),
                'update_time'=>time(),
            ];
            $up = model('indexUser')->update($data);
            if ($up!==false){
                session('timeout',null);
                session('code',null);
                $this->success('修改成功','user/login');
            }else{
                $this->error('修改失败');
            }
        }
        $this->assign([
           'id'=>$id,

        ]);
        return $this->fetch('',[
            'title'=>'修改密码'
        ]);
    }

    public function register()
    {
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
//            if(empty($info['captcha'])){
//                return $this->error('请输入验证码');
//                //验证码错误
//            }elseif (!captcha_check($info['captcha'])){
//                return $this->error('验证码错误');
//            }
//            if ($info['password']!==$info['sepassword']){
//                return $this->error('两次输入密码不一致');
//            }elseif(empty($info['password'])||empty($info['sepassword'])){
//                return $this->error('请输入密码');
//            }
            try{
                $up = model('indexUser')->sav($info);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if ($up){
                $this->success('注册成功','user/login');
            }else{
                $this->error('注册失败');
            }

//            if ($up){
//                $this->success('注册成功','user/login');
//            }else{
//                $this->error('注册失败');
//            }
        }else{
            return $this->fetch('',[
                'title'=>'注册'
            ]);
        }

    }

    public function edit($id){
        $this->isLogin();
        $data = model('indexUser')->find($id);
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
//            判断其中一个密码没写
            if (!empty($info['expassword'])&&!empty($info['password'])&&!empty($info['sepassword'])){
                $expassword = md5($info['expassword'].$data['code']);
//                var_dump($expassword);
//                var_dump($data['password']);die;
                if ($expassword!==$data['password']){
                    $this->error('原密码输入错误');
                }elseif($info['password']!==$info['sepassword']){
                    $this->error('两次密码输入不一致');
                }else{
                    $info['password'] = md5($info['sepassword'].$data['code']);
                }
            }elseif(empty($info['expassword'])&&!empty($info['password'])&&!empty($info['sepassword'])){
                $this->error('请先输入原密码再填写新密码');
            }elseif(!empty($info['expassword'])&&empty($info['password'])&&!empty($info['sepassword'])){
                $this->error('请输入新密码');
            }elseif(!empty($info['expassword'])&&!empty($info['password'])&&empty($info['sepassword'])){
                $this->error('请输入新密码');
            }else{
                $info['password'] = $data['password'];
            }

            $up =  model('indexUser')->dataup($info);
            if ($up!==false){
                $this->redirect('index/index');
            }else{
                $this->error('操作失败');
            }
        }
        $this->assign('data',$data);
        return $this->fetch();
    }


    public function logout(){
        session(null,'index');
        $this->success('退出成功','user/login');
    }
}
