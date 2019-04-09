<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午11:38

 * Description:
 */

namespace app\common\model;

use app\common\base\BaseModel;

class FuncModel extends BaseModel {
    protected $table = 'func';

    public static function instance() {
        return new FuncModel();
    }

    public function findFunc($condition) {
        return $this->where($condition)->find();
    }

    public function addFunc($data) {
        return $this->save($data);
    }

    public function getFunc($condition=[]) {
        return $this->getCond($condition, $this->table)->select();
    }

    public function upFunc($condition,$data) {
        return $this->where($condition)->update($data);
    }

    public function delFunc($condition) {
        return $this->where($condition)->delete();
    }
}