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

class Permission extends Base
{



    public function index(){
        $sessionuser = session('user','','admin');

        $tree = model('permission')->gettree();
        $this->assign('userid',$sessionuser['id']);
        $this->assign('tree',$tree);
        return $this->fetch();
}

    public function add(){
        $tree = model('permission')->gettree();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $info['status'] = 1;
            $up = model('permission')->sav($info);
            if ($up){
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('添加失败');
            }
        }
        $this->assign([
            'tree'=>$tree
        ]);
        return $this->fetch();
    }


    public function edit($id){
        $tree = model('permission')->gettree();
        $data = model('permission')->find($id);
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            if ($info['pid']==$data['id']){
                $this->error('选择失败，请重新选择');
            }
            $arr = model('permission')->getParentId($info['pid']);
//            var_dump($arr);die;
            if (in_array($info['id'],$arr)){
                $this->error('选择失败，请重新选择');
            }
            $up = model('permission')->up($info);
            if ($up){
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('添加失败');
            }
        }
        $this->assign([
            'tree'=>$tree,
            'data'=>$data,

        ]);
        return $this->fetch();
    }
}
