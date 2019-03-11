<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\Bis as Bism;
use app\common\model\BisAccount;
use app\common\model\BisLocation;
use app\common\model\Category;
use app\common\model\City;
class Bis extends Base
{
    public function index()
    {
        $bism = new Bism();
        $data = $bism->getbislist();
//        var_dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }


    public function apply()
    {
        $bism = new Bism();
        $data = $bism->getbisadd();
//        var_dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }


    public function dellist()
    {
        $bism = new Bism();
        $data = $bism->getbisdel();
//        var_dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function status()
    {
        $info['id'] = input('id');
        $info['status'] = input('status');
        $bisinfo = db('bis')->find($info['id']);
        $del1 = db('bis')->where('id', $info['id'])->update(['update_time' => time(), 'status' => $info['status']]);
        $del2 = db('bisLocation')->where('bis_id', $info['id'])->update(['update_time' => time(), 'status' => $info['status']]);
        $del3 = db('bisAccount')->where('bis_id', $info['id'])->update(['update_time' => time(), 'status' => $info['status']]);
        if ($info['status']==1){
            $url = request()->domain().url('index.php/bis/login/index');
            $title = 'o2o商户审核成功';
            $content = "点击链接登录个人商户中心
                <a href='" . $url . "' target='_blank'>查看链接</a>";
            \phpmailer\Email::send($bisinfo['email'],$title,$content);
        }
        if ($del1&&$del2&&$del3) {
            $this->success('操作成功', 'bis/index');
        }
    }


    public function detail($id){
        $cate = new Category();
        $city = new City();
        $bism = new Bism();
        $bisaccm = new BisAccount();
        $bislocam = new BisLocation();
        $cates = $cate->getlis();
        $citys = $city->getcity();
//        $secitys = $city->getsecity();
        $bisdata = $bism->get($id);
        $account = $bisaccm->where('bis_id',$id)->find();
        $location = $bislocam->where('bis_id',$id)->find();


        $this->assign([
            'bisdata'=>$bisdata,
            'cates'=>$cates,
            'citys'=>$citys,
//            'secitys'=>$secitys,
            'account'=>$account,
            'location'=>$location,

        ]);
         return $this->fetch();
    }

    public function edit($id){
        if (!$id){
            $this->error('参数错误');
        }
        $cate = new Category();
        $city = new City();
        $cates = $cate->getlis();
        $citys = $city->getcity();
        $bis = model('bis')->find($id);
        $location = model('BisLocation')->where(['bis_id'=>$id,'is_main'=>1])->find();
        $account = model('BisAccount')->where(['bis_id'=>$id])->find();
        //        var_dump($location);die;

        if (strstr($bis['city_path'],',')){
            $pos = stripos($bis['city_path'],',');
            $secity = $pos?substr($bis['city_path'],$pos+1):'';
        }else{
            $secity = '';
        }

        $allcity = model('city')->where(['pid'=>$bis['city_id'],'status'=>1])->select();

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
            $data = input('post.','','htmlentities');
            $bism = new Bism;
            $bisaccountm = new BisAccount();
            $bislocationm = new BisLocation();
            if (request()->isPost()){
                $bisid = $bism->edit($data);
//                $data['bis_id'] = $bisid;
                $bislocationid = $bislocationm->up($data);
                $accdata = model('BisAccount')->find($data['id']);
                $bisaccountid = $bisaccountm->dataup($data,$accdata);

                if (!$bisaccountid){
//                    $bism->destroy($bisid);
//                    $bislocationm->destroy($bislocationid);
//                    $bisaccountm->destroy($bisaccountid);
                    $this->error('修改失败');
                }
                if ($bislocationid&&$bisid){

                    $this->success('修改成功','bis/index');
                }else{
                    $this->error('修改失败');
                }
            }
        }

        $this->assign('secity',$secity);
        $this->assign('allcity',$allcity);
        $this->assign('bis',$bis);
        $this->assign('account',$account);
        $this->assign('location',$location);
        $this->assign('cates',$cates);
        $this->assign('citys',$citys);
        $this->assign('allsecate',$allsecate);//全部子分类
        $this->assign('secategory',$secategory);//商品选定的子分类
        return $this->fetch();
    }
}
