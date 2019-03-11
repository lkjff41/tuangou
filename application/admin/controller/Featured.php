<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/6
 * Time: 22:39
 */

namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\Featured as featuredModel;

class Featured extends Base
{

    public function index(){
        $types = config('featured.featured_type');
        if (request()->isGet()){
            $type = input('get.type');
            $data = model('featured')->gettype($type);
        }

//        $data = db('featured')->select();
        $this->assign('data',$data);
        $this->assign('types',$types);
        $this->assign('typeid',empty($type)?'':$type);
        return $this->fetch();
    }



    public function add(){
        $featuredm = new featuredModel();
        $types = config('featured.featured_type');
        if (request()->isPost()){
            $info = input('post.');
            $up = $featuredm->sav($info);
            if ($up){
                $this->success('添加成功','featured/index');
            }else{
                $this->error('操作失败');
            }
        }
        $this->assign('types',$types);
        return $this->fetch();
    }

    public function edit(){
        $types = config('featured.featured_type');
        $id = input('id');
        if (request()->isPost()){
            $data = input('post.','','htmlentities');
            $data['update_time'] = time();
            $up = model('featured')->up($data);
            if ($up!==false){
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('操作失败');
            }
        }
        $data = model('featured')->find($id);
        $this->assign('data',$data);
        $this->assign('types',$types);
        return $this->fetch();
    }

}