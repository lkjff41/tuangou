<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\Category as cateModel;
class Category extends Base
{
    public function index()
    {
        $lis = db('category')->where(['pid' => 0, 'status' => ['neq', -1]])
            ->order('sort desc')->paginate(15);
        $this->assign('cate', $lis);
        return $this->fetch();
    }

    //获取子分类
    public function getchil()
    {
        $pid = input('pid');
        $cate = new cateModel();
        $lis = $cate->getchilcate($pid);
//        var_dump($pid);die;
        $this->assign('cate', $lis);
        return $this->fetch('index');
    }

    public function add()
    {
        $cate = new cateModel();
        $info = input('post.','','htmlentities');

        if (request()->isPost()) {
            $validate = validate('Category');
            if (!$validate->scene('add')->check($info)) {
                $this->error($validate->getError());
                die;
            }
            $sav = $cate->sav($info);

            if ($sav) {
                echo '<script>window.parent.location.reload()</script>';
            } else {
                $this->error('添加失败');
            }
        }
        $catetree = $cate->catetree();
        $this->assign('cate', $catetree);
        return $this->fetch();
    }

    public function edit()
    {
        $cate = new cateModel();
        $id = input('id');
        $data = db('category')->find($id);
        //显示无限分类下拉框
        $catetree = $cate->catetree();
        $info = input('post.','','htmlentities');
        if (request()->isPost()) {
            $info['update_time'] = time();
            $validate = validate('Category');
            if (!$validate->scene('edit')->check($info)) {
                $this->error($validate->getError());
                die;
            }
            $up = db('category')->update($info);

            if ($up!==false) {
                echo '<script>window.parent.location.reload()</script>';
            } else {
                $this->error('修改失败');
            }
        }

        $this->assign('cate', $catetree);
        $this->assign('data', $data);
        return $this->fetch();
    }



}