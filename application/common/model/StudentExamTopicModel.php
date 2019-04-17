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
        return $this->where($condition)->order('ctime', 'desc')->paginate(20, false ,['query' => [
            'exam_topic_id' => $condition['exam_topic_id']
        ]]);
    }

    public function addStudentExamTopic($data) {
        return $this->save($data);
    }

    public function up($condition, $data) {
        return $this->where($condition)->update($data);
    }

    public function countDeal() {
        $sql = "select exam_topic_id, status, count(*) as total from {$this->table} GROUP BY exam_topic_id,status";
        return $this->query($sql);
    }

    public function countPass() {
        $sql = "select exam_topic_id, count(*) as total from {$this->table} WHERE total_score>=60 GROUP BY exam_topic_id";
        return $this->query($sql);
    }

}