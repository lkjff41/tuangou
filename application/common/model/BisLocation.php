<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 20:55
 */

namespace app\common\model;
use app\api\controller\Register as apireg;
use think\Model;

class BisLocation extends Model
{
    public function sav($data){
        $registerm = new apireg();
        if (!empty($data['address'])) {
            $address = $registerm->getxy($data['address']);
            $xpoint = $address['result']['location']['lng'];
            $ypoint = $address['result']['location']['lat'];
        }
        $bislocation = [
            'xpoint'=>empty($xpoint)?'':$xpoint,
            'ypoint'=>empty($ypoint)?'':$ypoint,
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'address'=>$data['address'],
            'tel'=>$data['tel'],
            'contact'=>$data['contact'],
            'bis_id'=>$data['bis_id'],
            'open_time'=>$data['open_time'],
            'is_main'=>1,//总店信息
//            'api_address'=>$data['api_address'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'category_id'=>$data['category_id'],
            'category_path'=>empty($data['se_category_id'])?$data['category_id']:$data['category_id'].','.implode('|',$data['se_category_id']),
            'bank_info'=>$data['bank_info'],
            'content'=>empty($data['content'])?'':$data['content'],
            'status'=>0,
            'sort'=>80,
            'create_time'=>time(),
        ];

        $validate = new BisLocation();
        $result = $validate->validate(true)->save($bislocation);
        if (false===$result){
            dump($validate->getError());die;
        }

        return $this->getLastInsID();
    }

    /**
     * 分店添加
     * @param $data
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function fensav($data){
        $registerm = new apireg();
        if (!empty($data['address'])) {
            $address = $registerm->getxy($data['address']);
            $xpoint = $address['result']['location']['lng'];
            $ypoint = $address['result']['location']['lat'];
        }
        $bisdata = db('bis')->find(session('bisAccount')['bis_id']);
        $bislocation = [
            'xpoint'=>empty($xpoint)?'':$xpoint,
            'ypoint'=>empty($ypoint)?'':$ypoint,
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'address'=>$data['address'],
            'tel'=>$data['tel'],
            'contact'=>$data['contact'],
            'bis_id'=>session('bisAccount')['bis_id'],
            'open_time'=>$data['open_time'],
            'is_main'=>0,//总店信息
//            'api_address'=>$data['api_address'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'category_id'=>$data['category_id'],
            'category_path'=>empty($data['se_category_id'])?$data['category_id']:$data['category_id'].','.implode('|',$data['se_category_id']),
            'bank_info'=>$bisdata['bank_info'],
            'content'=>empty($data['content'])?'':$data['content'],
            'status'=>$data['status'],
            'sort'=>50,
            'create_time'=>time(),
        ];

        $validate = new BisLocation();
        $result = $validate->validate(true)->save($bislocation);
        if (false===$result){
            dump($validate->getError());die;
        }

        return $this->getLastInsID();
    }

    public function up($data){
        $registerm = new apireg();
        if (!empty($data['address'])) {
            $address = $registerm->getxy($data['address']);
            $xpoint = $address['result']['location']['lng'];
            $ypoint = $address['result']['location']['lat'];
        }
//        $bisdata = db('bis')->find(session('bisAccount')['bis_id']);
        $bislocation = [
            'id'=>$data['locationid'],
            'xpoint'=>empty($xpoint)?'':$xpoint,
            'ypoint'=>empty($ypoint)?'':$ypoint,
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'address'=>$data['address'],
            'tel'=>$data['tel'],
            'contact'=>$data['contact'],
//            'bis_id'=>session('bisAccount')['bis_id'],
            'open_time'=>$data['open_time'],
//            'is_main'=>0,//总店信息
//            'api_address'=>$data['api_address'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'category_id'=>$data['category_id'],
            'category_path'=>empty($data['se_category_id'])?$data['category_id']:$data['category_id'].','.implode('|',$data['se_category_id']),
//            'bank_info'=>$bisdata['bank_info'],
            'content'=>empty($data['content'])?'':$data['content'],
//            'status'=>$data['status'],
//            'sort'=>50,
            'update_time'=>time(),
        ];

        $validate = new BisLocation();
        $result = $validate->validate('BisLocation.edit')->update($bislocation);
        if (false===$result){
            dump($validate->getError());die;
        }else{
            return $result['id'];
        }

//        return $this->getLastInsID();
    }
}