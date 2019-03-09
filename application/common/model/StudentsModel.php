<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/9 下午3:30
 * Description: 考生模型
 */
namespace app\common\model;

use app\common\base\BaseModel;

class StudentsModel extends BaseModel {

    protected $table='students';

    public static function instance() {
        return new StudentsModel();
    }

    public function getList($data=[]) {
        $res = $this->getCond($data, $this->table)->order('ctime', 'desc')->paginate(20);
        return $res;
    }

    public function addStudent($data) {
        return $this->save($data);
    }

    public function upStudent($condition, $data) {
        $res = $this->where($condition)->update($data);
        return $res;
    }

}