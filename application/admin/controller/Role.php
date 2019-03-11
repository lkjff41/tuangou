<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/25
 * Time: 22:33
 */

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Controller;

class Role extends Base
{
    public function index(){
        $sessionuser = session('user','','admin');

        $data = model('role')->where(['status'=>1])->order('sort desc')->select();
        $this->assign('userid',$sessionuser['id']);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function add(){
        $tree = model('permission')->gettree();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $info['status'] = 1;
            $up = model('role')->sav($info);
            if ($up){
                if (!empty($info['permission_id'])){
                    foreach ($info['permission_id'] as $k=>$v){
                        model('rolePermission')->insert([
                            'role_id'=>$up,
                            'permission_id'=>$v,
                        ]);
                    }
                }
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('添加失败');
            }
        }
        $this->assign([
            'tree'=>$tree,

        ]);
        return $this->fetch();
    }

    public function edit($id){
        $tree = model('permission')->gettree();
        $data = model('role')->find($id);
        $pers = db('rolePermission')->where(['role_id'=>$data['id']])->field('group_concat(permission_id) as permission_id')->find();
        $pers = explode(',',$pers['permission_id']);
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $info['status'] = $data['status'];
            $up = model('role')->up($info);

            if ($up!==false){

                db('rolePermission')->where('role_id',$data['id'])->delete();
                if (!empty($info['permission_id'])){
                    foreach ($info['permission_id'] as $k=>$v){
                        model('rolePermission')->insert([
                            'role_id'=>$up,
                            'permission_id'=>$v,
                        ]);
                    }
                }
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('添加失败');
            }
        }
        $this->assign([
            'data'=>$data,
            'tree'=>$tree,
            'pers'=>$pers,
        ]);
        return $this->fetch();
    }

    public function user($id){
        $user = model('user')->where(['status'=>1])->order('sort desc')->select();
//        $data = model('user')->find($id);
        $users = db('userRole')->where(['role_id'=>$id])->field('group_concat(user_id) as user_id')->find();
        $users = explode(',',$users['user_id']);
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            db('userRole')->where('role_id',$id)->delete();
            if (!empty($info['user_id'])){
                foreach ($info['user_id'] as $k=>$v){
                    model('userRole')->insert([
                        'role_id'=>$id,
                        'user_id'=>$v,
                    ]);
                }
            }
            echo '<script>window.parent.location.reload()</script>';
        }
        $this->assign([
//            'data'=>$data,
            'id'=>$id,
            'user'=>$user,
            'users'=>$users,

        ]);
        return $this->fetch();
    }
}
