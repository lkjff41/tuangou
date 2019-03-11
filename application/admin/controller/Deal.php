<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/5
 * Time: 13:33
 */

namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\City;
use app\common\model\Category;
use app\common\model\Deal as dealModel;
class Deal extends Base
{
    public function index(){
        $citym = new City();
        $catem = new Category();
        $city = $citym->catetree();
        $cate = $catem->getlis();
        $data = db('deal')->where(['status'=>1])->order('sort desc')->paginate(15);
        $this->assign('city',$city);
        $this->assign('cate',$cate);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function apply(){
        $citym = new City();
        $catem = new Category();
        $city = $citym->catetree();
        $cate = $catem->getlis();
        $data = db('deal')->where(['status'=>0])->order('sort desc')->paginate(15);
        $this->assign('data',$data);
        $this->assign('city',$city);
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    public function search(){
        $dealm = new dealModel();
        $citym = new City();
        $catem = new Category();
        $city = $citym->catetree();
        $cate = $catem->getlis();
        if (request()->isGet()){
            $info = input('get.','','htmlentities');
            $data = $dealm->sou($info);
        }
        $this->assign([
            'city_id'=>empty($info['city_id'])?'':$info['city_id'],
            'category_id'=>empty($info['category_id'])?'':$info['category_id'],
            'start_time'=>empty($info['start_time'])?'':$info['start_time'],
            'end_time'=>empty($info['end_time'])?'':$info['end_time'],
            'name'=>empty($info['name'])?'':$info['name'],
            'status'=>empty($info['status'])?'':$info['status'],
        ]);
        $this->assign('city',$city);
        $this->assign('cate',$cate);
        $this->assign('data',$data);
        return $this->fetch();


    }

    public function look($id){
        $cates = model('category')->getlis();
        $citys = model('city')->getcity();
        $data = model('deal')->find($id);
        $pid = model('city')->getParentId($data['city_id']);

        if (count($pid)==2){
            $cityname = model('city')->find($pid[1]);
            $secityname = model('city')->find($pid[0]);
        }elseif(count($pid)==1){
            $cityname = model('city')->find($pid[0]);
            $secityname = '';
        }
//        var_dump($pid);die;
//        $pos = stripos($data['se_city_id'],',');
//        $secitys = $pos?substr($data['se_city_id'],$pos+1):'';
//        if ($secitys){
//            $secity =  model('city')->field('id,name')->find($secitys);
//        }

        $pos = stripos($data['se_category_id'],',');
        $secates = $pos?substr($data['se_category_id'],$pos+1):'';
        $secate = explode(',',$secates);
        static $secategory;
        foreach ($secate as $k=>$v){
            $secategory[] = model('category')
                ->field('id,name')
                ->find($v);
        }


        $location = explode(',',$data['location_ids']);
        static $dian;
        foreach ($location as $k=>$v){
            $dian[] = model('bisLocation')
                ->field('id,name')
                ->find($v);
        }

        $this->assign([
            'cates'=>$cates,
            'citys'=>$citys,
            'data'=>$data,
            'dian'=>$dian,
            'secategory'=>$secategory,
            'cityname'=>$cityname,
            'secityname'=>$secityname,
        ]);
        return $this->fetch();
    }

    public function edit($id){
        var_dump(md5(836989961616));die;
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
}