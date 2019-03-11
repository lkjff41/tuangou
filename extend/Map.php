<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 12:15
 百度地图api
 */

class Map{

    //获取坐标
    public static function  getLngLat($address){
//        http://api.map.baidu.com/geocoder/v2/?address
//        //北京市海淀区上地十街10号&output=json&ak=您的ak&callback=showLocation //GET请求
        if (!$address){
            return '';
        }
        $data = [
            'address'=>$address,
            'ak'=>config('map.ak'),
            'output'=>'json',
        ];
//        把数组转换成http参数
        $url = config('map.baidu_map_url').config('map.gencoder').'?'.http_build_query($data);
        $result = doCurl($url);
        if ($result){
            return json_decode($result,true);
//            return $result;
        }else{
            return '';
        }
//        return $result;
    }

//获取地图图片

    /**
     * @param $center 坐标xy 用逗号隔开
     * @return mixed|string
     */
    public static function staticimage($center){
        if (!$center){
            return '';
        }
        $data = [
            'ak'=>config('map.ak'),
            'width'=>config('map.width'),
            'height'=>config('map.height'),
            'center'=>$center,
            'markers'=>$center,

        ];
//        把数组转换成http参数
        $url = config('map.baidu_map_url').config('map.staticimage').'?'.http_build_query($data);
        $result = doCurl($url);
        return $result;
    }
}
