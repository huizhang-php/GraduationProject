<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午11:09
 * Description: 考试专题模型层
 */
namespace app\common\model;

use app\common\base\BaseModel;

class ExamTopicModel extends BaseModel {

    protected $table='exam_topic';

    public function examtopicquestionbank() {
        return $this->hasOne('ExamPaperModel', 'id', 'question_bank_id');
    }

    public static function instance() {
        return new ExamTopicModel();
    }

    public function add($data) {
        return $this->save($data);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:04
     * @param $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function getList($condition) {
        return $this->getCond($condition, $this->table)->paginate(20);
    }

    public function up($condition, $data) {
        return $this->where($condition)->update($data);
    }

}