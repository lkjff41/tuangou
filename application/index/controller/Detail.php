<?php
namespace app\index\controller;
use app\index\controller\Base;
class Detail extends Base{
    public function index($id)
    {
        if (!intval($id)){
            $this->error('Id不合法');
        }
//        团购信息
        $deal = model('deal')->find($id);
        if (!$deal||$deal['status']!=1){
            $this->error('该商品不存在');
        }
        //分类信息
        $category = db('category')->find($deal['category_id']);
//        总店信息
        $bis = db('bis')->find($deal['bis_id']);
        //分店信息
//        var_dump($deal);die;
        $All_location = db('bisLocation')
            ->where(['id'=>['in',$deal['location_ids']]])->select();

//        $a=$deal['start_time']-time();
//        var_dump($All_location);die;

        $this->assign([
            'deal'=>$deal,
            'category'=>$category,
            'bis'=>$bis,
            'All_location'=>$All_location,
            'xy'=>$deal['xpoint'].','.$deal['ypoint'],
        ]);
        return $this->fetch('',[
            'title'=>$deal['name']
        ]);
    }

}