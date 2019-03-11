<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 12:35
 */
namespace app\index\controller;
use think\Controller;

class Base extends Controller{
    public function _initialize()
    {
//        $this->isLogin();
        //地级市
        $citys1 = db('city')->where(['status'=>['neq',-1],'pid'=>['neq',0]])->select();
        //直辖市
        $citys2 = db('city')->where(['status'=>['neq',-1],'is_main'=>['eq',1]])->select();
        //合并城市
        $citys = array_merge($citys1,$citys2);
        $usersession = session('indexuser','','index');
        $list = $this->getCates();
        $this->getCity($citys);
        $this->assign('list',$list);
        $this->assign('user',$usersession);
        $this->assign('citys',$citys);
        $this->assign('city', $this->city);
        //根据控制器名称 动态载入css
        $this->assign('controller',strtolower(request()->controller()));
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function isLogin(){
        if (!session('indexuser','','index')){
            $this->error('请登录','user/login');
        }
    }

    //获取分类 整合成三维数组
    public function getCates(){
        $category = $seArr = $cate = [];
        $data = model('category')->catetree();
        foreach ($data as $k=>$v){
            if ($v['level']==0){
                $cate[] = $v;
            }
            foreach ($data as $k1=>$v1){
                if ($v1['pid']==$v['id']){
                    $secate[] = $v1;
                }
            }
        }
        foreach ($secate as $k2=>$v2) {
            //很重要 记住！！！！！！！！！！！！！！！
            $seArr[$v2['pid']][] = [
                'id' => $v2['id'],
                'name' => $v2['name'],
            ];
        }
        foreach ($cate as $k3=>$v3){
            $category[$v3['id']] = [$v3['name'],empty($seArr[$v3['id']])?[]:$seArr[$v3['id']]];
        }
        return $category;
    }

    public function getCity($citys){
        foreach ($citys as $k=>$v){
            if ($v['is_default']==1){
                $defaultuname = $v['uname'];
                break;
            }
        }
        $defaultuname = $defaultuname ? $defaultuname : 'guangzhou';
        if (session('cityuname')&&!input('city')){
            $cityuname = session('cityuname');
        }else{
            $cityuname = input('city',$defaultuname);
            session('cityuname',$cityuname);
        }
        $this->city = model('city')->where('uname',$cityuname)->find();

    }
}