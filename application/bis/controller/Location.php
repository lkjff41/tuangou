<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 11:19
 */

namespace app\bis\controller;
use app\bis\controller\Base;
use app\common\model\Category;
use app\common\model\City;
use app\common\model\BisLocation;
use app\common\model\BisAccount;

class Location extends Base{
    public function index(){
        $account = session('bisAccount','','bis');
        $data = db('bisLocation')
            ->where(['bis_id'=>$account['bis_id']])
            ->order('sort desc')->paginate(15);
        $this->assign('data',$data);
        $this->assign('account',$account);
        return $this->fetch();
    }

    public function add(){
        $cate = new Category();
        $city = new City();
        $locationm = new BisLocation();
        $cates = $cate->getlis();
        $citys = $city->getcity();
        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $locationid = $locationm->fensav($info);
            if ($locationid){
                $this->success('申请成功，等待审核','location/index');
            }else{
                $this->error('申请失败');
            }
        }

        $this->assign('cates',$cates);
        $this->assign('citys',$citys);
        return $this->fetch();
    }

    public function edit($id){
        if (!$id){
            $this->error('参数错误');
        }
        $cate = new Category();
        $city = new City();
        $locationm = new BisLocation();
        $cates = $cate->getlis();
        $citys = $city->getcity();
        $location = $locationm->find($id);

        if (strstr($location['city_path'],',')){
            $pos = stripos($location['city_path'],',');
            $secity = $pos?substr($location['city_path'],$pos+1):'';
        }else{
            $secity = '';
        }

        $allcity = model('city')->where(['pid'=>$location['city_id'],'status'=>1])->select();

        //获取当前类的所有子类
        $allsecate = db('category')
            ->where(['pid'=>$location['category_id'],'status'=>1])
            ->order('sort desc')->select();

//        商品子类
        if (strstr($location['category_path'],',')){
            $pos = stripos($location['category_path'],',');
            $secates = $pos?substr($location['category_path'],$pos+1):'';
            $secate = explode('|',$secates);
            static $secategory;
            foreach ($secate as $k=>$v){
                $secategory[] = model('category')
                    ->field('id,name')
                    ->find($v);
            }
        }else{
            $secategory = '';
        }


        if (request()->isPost()){
            $info = input('post.','','htmlentities');
//            var_dump($info);die;
            $up = $locationm->up($info);
            if ($up!==false){
                $this->success('修改成功','location/index');
            }else{
                $this->error('修改失败');
            }
        }
        $this->assign('cates',$cates);
        $this->assign('citys',$citys);
        $this->assign('location',$location);
        $this->assign('secity',$secity);
        $this->assign('allcity',$allcity);
        $this->assign('allsecate',$allsecate);//全部子分类
        $this->assign('secategory',$secategory);//商品选定的子分类

        return $this->fetch();
    }

    public function status()
    {
        $info['id'] = input('id');
        $info['status'] = input('status');
        //if is_main 不等于1 都失败
        if (session('bisAccount','','bis')['is_main']!=1){
            $this->error('你没有权限');
        }
        $del = db('bisLocation')->where('id', $info['id'])->update(['update_time'=>time(),'status'=>$info['status']]);
        if ($del){
            $this->success('操作成功','location/index');
        }else{
            $this->error('操作失败');
        }
    }
}