<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/9 下午10:35
 * Description:
 */
namespace app\common\model;

use app\common\base\BaseModel;

class StudentExamTopicModel extends BaseModel {

    protected $table = 'student_exam_topic';

    public function examtopicstudent() {
        return $this->hasOne('StudentsModel', 'id', 'student_id');
    }

    public static function instance() {
        return new StudentExamTopicModel();
    }

    public function getList($condition) {
        return $this->where($condition)->paginate(20, false ,['query' => [
            'exam_topic_id' => $condition['exam_topic_id']
        ]]);
    }
}