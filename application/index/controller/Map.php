<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 15:22
 */

namespace app\index\controller;
use think\Controller;

class Map extends Controller
{
    public function getMapImage($data){
        return \Map::staticimage($data);
    }
}