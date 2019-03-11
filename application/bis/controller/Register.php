<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 22:16
 */

namespace app\bis\controller;
use think\Controller;
//use app\bis\controller\Base;
use app\common\model\Category;
use app\common\model\City;
use app\common\model\Bis;
use app\common\model\BisAccount;
use app\common\model\BisLocation;

class Register extends Controller
{
    public function index(){
        $cate = new Category();
        $city = new City();
        $cates = $cate->getlis();
        $citys = $city->getcity();
        $this->assign('cates',$cates);
        $this->assign('citys',$citys);
        return $this->fetch();
    }


    public function add(){
        $data = input('post.','','htmlentities');
        $bism = new Bis;
        $bisaccountm = new BisAccount();
        $bislocationm = new BisLocation();
        if (request()->isPost()){
            $data['status']=0;
            $bisid = $bism->sav($data);

            $data['bis_id'] = $bisid;

            $bislocationid = $bislocationm->sav($data);
            $data['is_main'] = 1;
            $bisaccountid = $bisaccountm->sav($data);

            if (!$bisaccountid){
                $bism->destroy($bisid);
                $bislocationm->destroy($bislocationid);
                $bisaccountm->destroy($bisaccountid);
                $this->error('申请失败');
            }
            if ($bislocationid&&$bisid){
                $url = request()->domain().url('index.php/bis/register/waiting',['id'=>$bisid]);
                $title = 'o2o入驻申请通知';
                $content = "你提交的入驻申请需要等待平台审核，你可以通过点击链接
                <a href='" . $url . "' target='_blank'>查看链接</a>";
                \phpmailer\Email::send($data['email'],$title,$content);
                $this->success('申请成功','login/index');
            }else{
                $this->error('添加失败');
            }
        }
    }


    public function waiting($id){
//        $id = input('id');
//        var_dump($id);die;
        if (empty($id)){
            $this->error('error');
        }
        $bis = new Bis();
        $data = $bis->get($id);
//        var_dump($data);die;
        $this->assign('data',$data);
        return $this->fetch();
}



}