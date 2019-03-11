<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 21:45
 */

namespace app\admin\controller;


class IndexUser extends Base
{
    public function index(){
        if (request()->isGet()){
            $q = input('get.');
            $data = model('indexUser')->sou($q);
            $data['status'] = ['neq',2];
            if ($data){
                $list = model('indexUser')->where($data)->select();
            }else{
                $list = model('indexUser')->where(['status'=>['neq',2]])->select();
            }
        }
        $this->assign('data',$list);
        return $this->fetch();
    }


    public function edit($id){
        $data = model('indexUser')->find($id);
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
//            判断其中一个密码没写
            if (!empty($info['expassword'])&&!empty($info['password'])&&!empty($info['sepassword'])){
                $expassword = md5($info['expassword'].$data['code']);
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
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('操作失败');
            }
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function dellist(){
        if (request()->isGet()){
            $q = input('get.');
            $data = model('indexUser')->sou($q);
            $data['status'] = ['eq',2];
            if ($data){
                $list = model('indexUser')->where($data)->select();
            }else{
                $list = model('indexUser')->where(['status'=>['neq',2]])->select();
            }
        }        $this->assign('data',$list);
        return $this->fetch();
    }
}