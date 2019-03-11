<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 21:54
 */

namespace app\bis\controller;
use app\bis\controller\Base;
use app\common\model\BisAccount;

class Account extends Base
{
    public function index(){
        $account = session('bisAccount','','bis');
        if ($account['is_main']==1){
            $data = db('bisAccount')->where('bis_id',$account['bis_id'])->paginate(15);
        }else{
            $data = db('bisAccount')->where('id',$account['id'])->paginate(15);
        }
        $this->assign('account',$account);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function add(){
        if (session('bisAccount','','bis')['is_main']!=1){
            $this->error('你没有权限');
        }
        $BisAccountm = new BisAccount();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $info['bis_id'] = session('bisAccount','','bis')['bis_id'];
            $info['is_main']=0;
            $info['status']=0;
            $up = $BisAccountm->sav($info);
            if ($up){
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('添加失败');
            }
        }
        return $this->fetch();
    }
    public function edit($id){
        $BisAccountm = new BisAccount();
        $info = input('post.','','htmlentities');
        $data = $BisAccountm->find($id);
        if ($data['id']!=session('bisAccount','','bis')['id']&&session('bisAccount','','bis')['is_main']!=1){
            $this->error('你没有权限');
        }
        if (request()->isPost()){
            $up = $BisAccountm->dataup($info,$data);
            if ($up!==false){
                echo '<script>window.parent.location.reload()</script>';
            }else{
                $this->error('修改失败');
            }
        }
        $this->assign('data',$data);
        return$this->fetch();
    }

    public function status()
    {
        $BisAccountm = new BisAccount();
        $info['id'] = input('id');
        $info['status'] = input('status');
        $data = $BisAccountm->find($info['id']);
        if (session('bisAccount','','bis')['is_main']!=1){
            $this->error('你没有权限');
        }elseif ($data['is_main']==1){
            $this->error('管理员不能修改状态');
        }
        $del = db('bisAccount')->where('id', $info['id'])->update(['update_time'=>time(),'status'=>$info['status']]);
        if ($del){
            $this->redirect('account/index');
        }else{
            $this->error('操作失败');
        }
    }
}
