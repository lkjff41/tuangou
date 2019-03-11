<?php
namespace app\index\controller;
use app\index\controller\Base;

class Lists extends Base{
    public function index($cate=0,$secate=0)
    {
        $cates = model('category')->find($cate);
        if ($cate!='0'){
            if (!$cates||$cates['status']!=1){
                $this->error('该类不存在');
            }
        }

        if (request()->isGet()){
            $order_sale = input('order_sale','');
            $order_price = input('order_price','');
            $order_time = input('order_time','');
            $sort='';
            if (!empty($order_sale)){
                $sort = $order_sale;
            }elseif(!empty($order_price)){
                $sort = $order_price;
            }elseif(!empty($order_time)){
                $sort = $order_time;
            }
            $secates = model('category')->getchilrenid($cate);
            $deal = model('deal')->getListShop($cate,$secate,$this->city->id,$sort);

        }

        $this->assign([
            'cateid'=>$cate,
            'cates'=>$cates,//本类信息
            'secates'=>$secates,//本类的所有子分类
            'secateid'=>$secate,//get获取的子分类id
            'deal'=>$deal,
            'sort'=>$sort,//排序名

        ]);
        return $this->fetch('',[
            'title'=>$cates['name'],
        ]);
    }


}
