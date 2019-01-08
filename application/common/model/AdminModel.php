<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午4:52

 * Description:
 */
namespace app\common\model;
use think\Model;
class AdminModel extends Model {

    protected $table = 'admin';

    public function findAdmin($condition) {
        return $this->where($condition)->find();
    }
}