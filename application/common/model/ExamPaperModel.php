<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/6 下午7:28
 * Description:
 */

namespace app\common\model;

use think\Model;

class ExamPaperModel extends Model {

    protected $table='exam_paper';

    public static function instance() {
        return new ExamPaperModel();
    }

    public function getList($condition=[]) {
        return $this->where($condition)->select();
    }

    public function add($data) {
        return $this->save($data);
    }

    public function up($condition, $data) {
        return $this->where($condition)->update($data);
    }
}
