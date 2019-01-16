<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午11:09
 * Description:
 */
namespace app\common\model;

use think\Model;

class RoleModel extends Model {

    protected $table='role';

    public function add($data) {
        return $this->save($data);
    }

    public function getList() {
        return $this->select();
    }
}