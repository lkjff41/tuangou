<?php
namespace app\common\model;
use think\Model;

class Category extends Model
{
    //一级栏目数据
    public function getlis(){
        $cate = $this->where(['pid'=>0,'status'=>['neq',-1]])->select();
        return $cate;
    }

    //二级栏目数据
    public function getselis(){
        $cate = $this->where(['pid'=>['neq',0],'status'=>['neq',-1]])->select();
        return $cate;
    }

    public function sav($info)
    {
        $info['status'] = 1;
        $info['create_time']=time();
        return $this->save($info);
    }
    //无限级分类
    public function catetree(){
        $cate = db('category')->where('status','neq',-1)->order('sort desc')->select();
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
    //获取子城市
    public function getchilcate($pid){
         $cate = $this->where(['pid'=>$pid,'status'=>['neq',-1]])
             ->order('sort desc')->paginate(15);
         return $cate;
    }








//    获取子id
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
//    只获取id
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
    public function getPid($id=0,$limit=5){
        $data = [
            'status'=>1,
            'pid'=>$id,
        ];
        $order = [
            'sort'=>'desc'
        ];
        return $this->where($data)->order($order)->limit($limit)->select();
    }

    public function getTwo($pid=0){
        $data = [
            'status'=>1,
            'pid'=>['in',implode(',',$pid)],
        ];
        $order = [
            'sort'=>'desc',
            'id'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }


}
