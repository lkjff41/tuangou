<?php
namespace app\admin\controller;
use app\admin\controller\Base;
class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }


    public function welcome()
    {
        echo 'hi';
//        \phpmailer\Email::send('799207363@qq.com','1','1');
//        return '成功';
    }

    public function logout(){
        session(null,'admin');
        $this->redirect('login/index');
    }
}
