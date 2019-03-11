<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//分页通用样式
function pagination($obj){
    if (!$obj){
        return '';
    }
    $params = request()->param();
    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';
}


function status($status){
    if ($status==1){
        $str = '<span class="label label-success radius">正常</span>';
    }elseif($status==0){
        $str = '<span class="label label-danger radius">待审</span>';
    }else{
        $str = '<span class="label label-danger radius">删除</span>';
    }
    return $str;
}

/**
 * @param $url
 * @param int $type 0是get 1是post
 * @param array $data
 */
function doCurl($url,$type=0,$data=[]){
    //初始化
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    if ($type==1){
        curl_setopt($ch,CURL_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    //执行
    $output = curl_exec($ch);
    //释放
    curl_close($ch);
    return $output;
}

function bisRegister($status){
    if ($status==1){
        $str = '入驻申请成功';
    }elseif($status==0){
        $str = '待审核，审核成功后平台会给你发送邮件';
    }elseif($status==2){
        $str = '资料不通过，请重新递交';
    }else{
        $str = '该申请已经删除';
    }
    return $str;
}

function getSeCityName($path){
    if (empty($path)&&strlen($path)>2){
        return '';
    }
    if (preg_match('/,/',$path)){
        $cityPath = explode(',',$path);
        $cityId = $cityPath[1];
    }else{
        $cityId = '';
    }
    if (!$cityId){
        return '';
    }
    $city = model('City')->find($cityId);
    return $city->name;
}

function getSeCateName($path){
    if (empty($path)){
        return '';
    }
    if (preg_match('/,/',$path)){
        $catePath = explode(',',$path);
        $cateId = $catePath[1];
    }else{
        $cateId = '';
    }
    if (!$cateId){
        return '';
    }
    $catePath = explode('|',$cateId);

    $name = [];
    foreach ($catePath as $k=>$v){
        $cate = model('category')->find($v);
       $name[] = $cate['name'];
    }
    return $name;
}


/**
 * 根据城市id获取城市名称
 * @param $id
 * @return mixed
 */
function cityName($id){
    $city = db('city')->find($id);
    return $city['name'];
}


/**
 * 根据栏目id获取城市名称
 * @param $id
 * @return mixed
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function cateName($id){
    $cate = db('category')->find($id);
    return $cate['name'];
}

/**
 * 根据分类 城市 来获取商品数据
 * @param $id 分类id
 * @param $cityId 城市id
 * @param int $limit 获取数量
 */
function getIndexShop($id,$cityId,$limit=10){
    $info = [
        'end_time'=>['gt',time()],
        'start_time'=>['lt',time()],
        'category_id'=>$id,
        'city_id'=>$cityId,
        'status'=>1,
    ];
    $order = [
        'sort'=>'desc',
        'id'=>'desc'
    ];
    $data =  db('deal')->where($info)->order($order)->limit($limit)->select();
    return $data;
}

/**
 * 主页获取分店数量
 * @param $ids
 * @return int
 */
function getLocationCount($ids){
    if(!$ids){
        return 1;
    }
    if (preg_match('/,/',$ids)){
        $arr = explode(',',$ids);
        return count($arr);
    }else{
        return 1;
    }
}

function getZongdian($id){
    $name = db('bis')->field('name')->find($id);
    return $name['name'];
}


/**
 *      把秒数转换为时分秒的格式
 *      @param Int $times 时间，单位 秒
 *      @return String
 */
function secToTime($times){
    $result = '00:00:00';
//    var_dump($times);die;
    if ($times>0) {
        $day = floor($times/86400);
        $hour = floor(($times-$day*86400)/3600);
        $minute = floor(($times-$day*86400-$hour*3600)/60);
//        $second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
        $result = $day.'天'.$hour.'小时'.$minute.'分钟';
    }
    return $result;
}

/**
 * 设置订单号  时间戳的随机数
 * @return string
 */
function setOrderSn(){
    list($t1,$t2) = explode(' ',microtime());
    $t3 = explode('.',$t1*10000);
    return $t2.$t3[0].(rand(10000,99999));
}
