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
use app\common\model\Deal as dealModel;

class Deal extends Base{
    public function index(){
        $account = session('bisAccount','','bis');
        $data = db('deal')->where(['bis_id'=>$account['bis_id']])
            ->order('sort desc')->paginate(15);
        $this->assign('data',$data);
        $this->assign('account',$account);
        return $this->fetch();
    }

    public function add(){
        $account = session('bisAccount','','bis');
        $cate = new Category();
        $city = new City();
        //获取栏目
        $cates = $cate->getlis();
        //获取城市
        $citys = $city->getcity();
        //获取当前门店
        $location = db('bisLocation')
            ->where(['bis_id'=>$account['bis_id'],'status'=>1])
            ->order('sort desc')->select();

        if (request()->isPost()){
            $info = input('post.','','htmlentities');
            $info['xpoint'] = $location[0]['xpoint'];
            $info['ypoint'] = $location[0]['ypoint'];
            $info['status'] = 0;
            $dealm = new dealModel();
            $up = $dealm->sav($info);
            if ($up){
                $this->success('添加成功','deal/index');
            }else{
                $this->error('操作失败');
            }
        }
        $this->assign('location',$location);
        $this->assign('cates',$cates);
        $this->assign('citys',$citys);
        return $this->fetch();
    }

    public function edit($id){
        if (!$id){
            $this->error('参数错误');
        }
//        $account = session('bisAccount','','bis');
        $cate = new Category();
        $city = new City();
        //获取栏目
        $cates = $cate->getlis();
        //获取城市
        $citys = $city->getcity();

        $deal = model('deal')->find($id);

        $location = db('bisLocation')
            ->where(['bis_id'=>$deal['bis_id'],'status'=>1])
            ->order('sort desc')->select();


        //获取当前门店
//        $location = db('bisLocation')
//            ->where(['bis_id'=>$account['bis_id'],'status'=>1])
//            ->order('sort desc')->select();
//        var_dump($account);die;

//        本商品的支持门店
        $mylocation = explode(',',$deal['location_ids']);
        static $dian;
        foreach ($mylocation as $k=>$v){
            $dian[] = model('bisLocation')
                ->field('id,name')
                ->find($v);
        }

        //获取当前类的所有子类
        $allsecate = db('category')
            ->where(['pid'=>$deal['category_id'],'status'=>1])
            ->order('sort desc')->select();


//        商品子类
        if (strstr($deal['se_category_id'],',')){
            $pos = stripos($deal['se_category_id'],',');
            $secates = $pos?substr($deal['se_category_id'],$pos+1):'';
            $secate = explode(',',$secates);
            static $secategory;
            foreach ($secate as $k=>$v){
                $secategory[] = model('category')
                    ->field('id,name')
                    ->find($v);
            }
        }else{
            $secategory = '';
        }

//        如果是直辖市
        $pcity= $city->find($deal['city_id']);
        if ($pcity['pid']===0){
            $pcityid=$pcity['id'];
        }else{
            $pcityid=$pcity['pid'];
        }

        if ($pcity['pid']!==0){
            $allcity = $city->getchilcate($pcity['pid']);
        }else{
            $allcity = '';
        }
//--------------------------------------------------------------------
        if (request()->isPost()){
            $data = input('post.','','htmlentities');
            $data['update_time'] = time();
//            var_dump($data);die;
            try{
                $up = model('deal')->up($data);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

//            var_dump($up);die;
            if ($up!==false){
                $this->success('添加成功','deal/index');
            }else{
                $this->error('操作失败');
            }
        }
        $this->assign('location',$location);
        $this->assign('cates',$cates);
        $this->assign('citys',$citys);
        $this->assign('deal',$deal);
        $this->assign('dian',$dian);
        $this->assign('secategory',$secategory);//所有子分类
        $this->assign('pcityid',$pcityid);
        $this->assign('allsecate',$allsecate);//商品子分类
        $this->assign('allcity',$allcity);//商品城市
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
        $del = db('deal')->where('id', $info['id'])->update(['update_time'=>time(),'status'=>$info['status']]);
        if ($del){
            $this->success('操作成功','deal/index');
        }else{
            $this->error('操作失败');
        }
    }

}