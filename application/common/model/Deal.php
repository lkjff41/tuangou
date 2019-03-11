<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 22:32
 */

namespace app\common\model;
use think\Model;
use app\common\model\City;
use app\common\model\Category;
use think\Db;
class Deal extends Model
{
    public function sav($info){
        $dealinfo = [
            'xpoint'=>$info['xpoint'],
            'ypoint'=>$info['ypoint'],
            'name'=>$info['name'],
            'bis_id'=>session('bisAccount')['bis_id'],
            'city_id'=>empty($info['se_city_id'])?$info['city_id']:$info['se_city_id'],
//            'se_city_id'=>empty($info['se_city_id'])?$info['city_id']:$info['city_id'].','.$info['se_city_id'],
            'category_id'=>$info['category_id'],
            'se_category_id'=>empty($info['se_category_id'])?$info['category_id']:$info['category_id'].','.implode(',',$info['se_category_id']),
            'location_ids'=>empty($info['location_ids'])?'':implode(',',$info['location_ids']),
            'image'=>$info['image'],
            'start_time'=>strtotime($info['start_time']),
            'end_time'=>strtotime($info['end_time']),
            'coupons_begin_time'=>strtotime($info['coupons_begin_time']),
            'coupons_end_time'=>strtotime($info['coupons_end_time']),
            'origin_price'=>$info['origin_price'],
            'current_price'=>$info['current_price'],
//            'buy_count'=>$info['buy_count'],
            'total_count'=>$info['total_count'],
            'bis_account_id'=>session('bisAccount')['id'],
//            'balance_price'=>$info['total_count'],
            'desc'=>empty($info['desc'])?'':$info['desc'],
            'notes'=>empty($info['notes'])?'':$info['notes'],
            'status'=>$info['status'],
            'sort'=>50,
            'create_time'=>time(),
        ];
        $validate = new Deal();
        $result = $validate->validate(true)->save($dealinfo);
        if (false===$result){
            dump($validate->getError());die;
        }

        return $this->getLastInsID();
    }

    /**
     * 搜索
     * @param $info
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sou($info){
        $citym = new City();
        $categorym = new Category();
        $citypid = $citym->getchilrenidid($info['city_id']);
        $catepid = $categorym->getchilrenidid($info['category_id']);
        $citypid[] = $info['city_id'];
        $catepid[] = $info['category_id'];
//        var_dump($catepid);die;
        if (!empty($info['start_time']) && !empty($info['end_time']) &&
            strtotime($info['end_time']) > strtotime($info['start_time'])){
            $data['create_time'] = [
                ['gt',strtotime($info['start_time'])],
                ['lt',strtotime($info['end_time'])],
            ];
        }elseif (!empty($info['start_time'])&&empty($info['end_time'])){
            $data['create_time'] = ['gt',strtotime($info['start_time'])];
        }elseif(!empty($info['end_time'])&&empty($info['start_time'])){
            $data['create_time'] = ['lt',strtotime($info['end_time'])];
        }

        if (!empty($info['city_id'])){
            $data['city_id'] = ['in',$citypid];
        }

        if (!empty($info['category_id'])){
            $data['category_id'] = ['in',$catepid];
        }
//        if (!empty($info['start_time']) && !empty($info['end_time']) &&
//        strtotime($info['end_time']) > strtotime($info['start_time'])){
//            $data['create_time'] = [
//                ['gt',strtotime($info['start_time'])],
//                ['lt',strtotime($info['end_time'])],
//            ];
//        }elseif (!empty($info['start_time'])&&empty($info['end_time'])){
//            $data['create_time'] = ['gt',strtotime($info['start_time'])];
//        }elseif(!empty($info['end_time'])&&empty($info['start_time'])){
//            $data['create_time'] = ['lt',strtotime($info['end_time'])];
//        }

        if (!empty($info['name'])){
            $data['name'] = ['like','%'.$info['name'].'%'];
        }
        $data['status'] = $info['status'];
        $up = $this->where($data)
            ->order('sort desc')
//            ->paginate($listRows = 1, $simple = false, $config = ['query'=>$data]);
            ->select();
//        ->paginate(15);
        if ($up){
            return $up;
        }else{
            return '';
        }
    }

    /**
     * @param $cate 一级分类id
     * @param $secate 二级分类id
     * @param $cityid城市id
     * @return mixed
     */
    public function getListShop($cate,$secate,$cityid,$sort){

        if ($cate=='0'){
            $info = [
                'city_id'=>$cityid,
                'status'=>1,
                'end_time'=>['gt',time()],
            ];
        } elseif ($cate!==0&&$secate===0){
            $info = [
                'category_id'=>['eq',$cate],
                'city_id'=>$cityid,
                'status'=>1,
                'end_time'=>['gt',time()],
            ];
        }elseif($cate!==0&&$secate!==0){
            $info = [
                'category_id'=>['eq',$cate],
//                'se_category_id'=>['like','%'.$secate.'%'],
//                'find_in_set('.$secate.",se_category_id)",
                'city_id'=>$cityid,
                'status'=>1,

                'end_time'=>['gt',time()],
            ];
            $info[] = ['exp',Db::raw("FIND_IN_SET($secate,se_category_id)")];
        }


        if (!empty($sort)){
            $order = [$sort=>'desc'];
        }else{
            $order = ['sort'=>'desc'];
        }
        $deal = db('deal')->where($info)->order($order)->paginate(20);
        return $deal;
    }


    public function up($info){
        if(empty($info['location_ids'])) {
            exception('支持门店不能为空');
        }
        $dealinfo = [
            'id'=>$info['id'],
            'name'=>$info['name'],
            'city_id'=>empty($info['se_city_id'])?$info['city_id']:$info['se_city_id'],
//            'se_city_id'=>empty($info['se_city_id'])?$info['city_id']:$info['city_id'].','.$info['se_city_id'],
            'category_id'=>$info['category_id'],
            'se_category_id'=>empty($info['se_category_id'])?$info['category_id']:$info['category_id'].','.implode(',',$info['se_category_id']),
            'location_ids'=>empty($info['location_ids'])?'':implode(',',$info['location_ids']),
            'image'=>$info['image'],
            'start_time'=>strtotime($info['start_time']),
            'end_time'=>strtotime($info['end_time']),
            'coupons_begin_time'=>strtotime($info['coupons_begin_time']),
            'coupons_end_time'=>strtotime($info['coupons_end_time']),
            'origin_price'=>$info['origin_price'],
            'current_price'=>$info['current_price'],
//            'buy_count'=>$info['buy_count'],
            'total_count'=>$info['total_count'],
//            'balance_price'=>$info['total_count'],
            'desc'=>empty($info['desc'])?'':$info['desc'],
            'notes'=>empty($info['notes'])?'':$info['notes'],
            'update_time'=>$info['update_time']
        ];
        $validate = new Deal();
        $result = $validate->validate('Deal.edit')->update($dealinfo);
        if (false===$result){
            dump($validate->getError());die;
        }else{
            return $result['id'];
        }
//        return $this->getLastInsID();
//        return $result;
    }
}