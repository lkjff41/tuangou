<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 22:04
 */
namespace app\bis\controller;
use app\bis\controller\Base;

class Index extends Base{
    public function index(){
        $sessionbis = session('bisAccount','','bis');
        $this->assign('sessionbis',$sessionbis);
       return $this->fetch();
    }

    public function logout(){
        session(null,'bisAccount','','bis');
        $this->redirect('login/index');
    }
}