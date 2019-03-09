<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午11:38

 * Description:
 */

namespace app\common\model;

use think\Model;

class FuncModel extends Model {
    protected $table = 'func';

    public function findFunc($condition) {
        return $this->where($condition)->find();
    }

    public function addFunc($data) {
        return $this->save($data);
    }

    public function getFunc() {
        return $this->order('ctime', 'desc')->select();
    }

    public function upFunc($condition,$data) {
        return $this->where($condition)->update($data);
    }

    public function delFunc($condition) {
        return $this->where($condition)->delete();
    }
}