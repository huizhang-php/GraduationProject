<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午11:09
 * Description: 考试专题模型层
 */
namespace app\common\model;

use think\Model;

class ExamTopicModel extends Model {

    protected $table='exam_topic';

    public static function instance() {
        return new ExamTopicModel();
    }

    public function add($data) {
        return $this->save($data);
    }

    public function getList($condition) {
        return $this->where($condition)->select();
    }

    public function up($condition, $data) {
        return $this->where($condition)->update($data);
    }

}