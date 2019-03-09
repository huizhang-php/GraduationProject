<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/9 下午4:14
 * Description:
 */
namespace app\common\model;

use app\common\base\BaseModel;

class StudentExamTopicModel extends BaseModel {

    protected $table = 'student_exam_topic';

    public static function instance() {
        return new StudentExamTopicModel();
    }

    public function addStudentExamTopic($data) {
        return $this->save($data);
    }
}