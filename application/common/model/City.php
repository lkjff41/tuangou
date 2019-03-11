<?php
namespace app\common\model;
use think\Model;

class City extends Model
{
    //一级城市目录
    public function getcity(){
        $data = $this->where(['pid'=>0,'status'=>['neq',-1]])->select();
        return $data;
    }


    //添加城市
    public function sav($info)
    {
        if (!empty($info['is_main'])){
            $info['is_main'] = 1;
        }
        $info['status'] = 1;
        $info['sort'] =50;
        $info['create_time']=time();
        return $this->save($info);
    }

    //无限级分类树形结构
    public function catetree(){
        $cate = $this->where('status','neq',-1)->order('sort desc')->select();
        return $this->sort($cate);
    }

    //无限级分类
    public function sort($data,$pid=0,$level=0){
        static $arr=[];
        foreach ($data as $k=>$v){
            if ($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->sort($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

    //获取子栏目
    public function getchilcate($pid){
         $cate = $this->where(['pid'=>$pid,'status'=>['neq',-1]])
             ->order('sort desc')->paginate(15);
         return $cate;
    }

//    获取子分类 id $arr[]=$v['id']
    public function getchilrenid($id){
        $data = $this->where(['status'=>1])->select();
        return $this->_getchilrenid($data,$id);
    }

    public function _getchilrenid($data,$id){
        static $arr = [];
        foreach ($data as $k=>$v){
            if ($v['pid']==$id){
                $arr[] = $v;
                $this->_getchilrenid($data,$v['id']);
            }
        }
        return $arr;
    }
// id $arr[]=$v['id']  只取id
    public function getchilrenidid($id){
        $data = $this->where(['status'=>1])->select();
        return $this->_getchilrenidid($data,$id);
    }

    public function _getchilrenidid($data,$id){
        static $arr = [];
        foreach ($data as $k=>$v){
            if ($v['pid']==$id){
                $arr[] = $v['id'];
                $this->_getchilrenidid($data,$v['id']);
            }
        }

        return $arr;
    }

//获取父id
    public function getParentId($id){
        $data = $this->where(['status'=>1])->select();
        return $this->_getParentId($data,$id);
    }
    public function _getParentId($data,$id){
        static $arr = [];
        foreach ($data as $k=>$v){
            if ($v['id']==$id){
                $arr[] = $v['id'];
                $this->_getParentId($data,$v['pid']);
            }
        }
        return $arr;
    }

}
