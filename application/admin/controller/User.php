<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 21:45
 */

namespace app\admin\controller;
use app\admin\controller\Base;

class User extends Base
{
    public function index(){
        $sessionuser = session('user','','admin');
        if (request()->isGet()){
            $q = input('get.');
            $data = model('user')->sou($q);
            $data['status'] = ['neq',2];
            if (session('user','','admin')['id']!=1){
                $data['id'] = ['eq',session('user','','admin')['id']];
            }
            if ($data){
                $list = model('user')->where($data)->select();
            }else{
                $list = model('user')->where(['status'=>['neq',2]])->select();
            }
        }
        $this->assign('userid',$sessionuser['id']);
        $this->assign('data',$list);
        return $this->fetch();
    }

    public function add(){
        $role = model('role')->where(['status'=>1])->order('sort desc')->select();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            try{
                $up = model('user')->sav($info);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if ($up){
                if (!empty($info['role_id'])){
                    foreach ($info['role_id'] as $k=>$v){
                        model('userRole')->insert([
                            'user_id'=>$up,
                            'role_id'=>$v,
                        ]);
                    }
                }
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('添加失败');
            }
        }
        $this->assign([
            'role'=>$role,

        ]);
        return $this->fetch();
    }

    public function edit($id){

//        if($id!=session('user','','admin')['id']){
//            $this->error('你没权限访问');
//        }

        $role = model('role')->where(['status'=>1])->order('sort desc')->select();
        $data = model('user')->find($id);
        $roles = db('userRole')->where(['user_id'=>$data['id']])->field('group_concat(role_id) as role_id')->find();
        $roles = explode(',',$roles['role_id']);
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
            $info['status'] = $data['status'];
            $up =  model('user')->dataup($info);
            if ($up!==false){
                db('userRole')->where('user_id',$data['id'])->delete();
                if (!empty($info['role_id'])){
                    foreach ($info['role_id'] as $k=>$v){
                        model('userRole')->insert([
                            'user_id'=>$up,
                            'role_id'=>$v,
                        ]);
                    }
                }
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('操作失败');
            }
        }
        $this->assign([
            'data'=>$data,
            'role'=>$role,
            'roles'=>$roles,

        ]);
        return $this->fetch();
    }



    public function dellist(){
        if (request()->isGet()){
            $q = input('get.');
            $data = model('user')->sou($q);
            $data['status'] = ['eq',2];
            if ($data){
                $list = model('user')->where($data)->select();
            }else{
                $list = model('user')->where(['status'=>['neq',2]])->select();
            }
        }        $this->assign('data',$list);
        return $this->fetch();
    }
}
