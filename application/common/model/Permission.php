<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 15:20
 */

namespace app\common\model;


use think\Model;

class Permission extends Model
{
    public function checkP($session){
        $userid = $session['id'];
        if ($userid==1){
            return true;
        }else{
            $has = model('userRole')->alias('a')
                ->join('rolePermission b','a.role_id=b.role_id')
                ->join('permission c','c.id=b.permission_id')
                ->where([
                    'a.user_id'=>$userid,
                    'c.model'=>strtolower(request()->module()),
                    'c.controller'=>strtolower(request()->controller()),
                    'c.action'=>strtolower(request()->action()),
                ])->count();
            return $has;
        }
    }

    public function sav($info){
        $validate = new Permission();
        $result = $validate->validate(true)->save($info);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $this->getLastInsID();
    }

    public function up($info){
        $validate = new Permission();
        $result = $validate->validate('Permission.edit')->update($info);
        if (false===$result){
            dump($validate->getError());die;
        }
        return $result['id'];
    }

    public function getmenutree($data){
        return $this->sortmenu($data);
    }

    public function sortmenu($data,$pid=0,$level=0){
        static $arr = [];
        foreach ($data as $k=>$v){
            if ($v['pid']==$pid){
                $v['level'] = $level;
                $arr[] = $v;
                $this->sortmenu($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }


    public function gettree(){
        $data = $this->select();
        return $this->sort($data);
    }

    public function sort($data,$pid=0,$level=0){
        static $arr = [];
        foreach ($data as $k=>$v){
            if ($v['pid']==$pid){
                $v['level'] = $level;
                $arr[] = $v;
                $this->sort($data,$v['id'],$level+1);
            }
        }
//        var_dump($arr);die;
        return $arr;
    }

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