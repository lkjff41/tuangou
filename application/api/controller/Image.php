<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 22:51
 */

namespace app\api\controller;
use think\Controller;
use think\File;
class Image extends Controller
{
    public function upload(){
        $file = request()->file('file');
        $info = $file->move('uploads');
        $key = $_POST['key'];
        $key2 = $_POST['key2'];
        if ($info && $info->getPathname()){
            return show(1,'success',DS.$info->getPathname());
        }
        echo $key;
        echo $key2;
    }
//    public function upload(){
//        $filename = $_FILES['file']['name'];
//        $key = $_POST['key'];
//        $key2 = $_POST['key2'];
//        if ($filename) {
//            move_uploaded_file($_FILES["file"]["tmp_name"],
//                "uploads/" . $filename);
//        }
//        echo $key;
//        echo $key2;
//    }
}