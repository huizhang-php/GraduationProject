<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  学生-考试专题model层
 */
namespace app\common\model;

use app\common\base\BaseModel;
use app\common\config\SelfConfig;
use app\common\tool\EsLog;

class StudentExamTopicModel extends BaseModel {

    /**
     * 学生-考试专题中间表
     *
     * @var string
     * CreateTime: 2019/4/27 下午11:12
     */
    protected $table = 'student_exam_topic';

    /**
     * 考试专题-学生一对一关系
     *
     * @return \think\model\relation\HasOne
     * CreateTime: 2019/4/27 下午11:12
     */
    public function examtopicstudent() {
        return $this->hasOne('StudentsModel', 'id', 'student_id');
    }

    /**
     * 返回当前实例
     *
     * @return StudentExamTopicModel
     * CreateTime: 2019/4/27 下午11:12
     */
    public static function instance() {
        return new StudentExamTopicModel();
    }

    /**
     * 获取信息
     *
     * @param $condition
     * @return bool|\think\Paginator
     * CreateTime: 2019/4/27 下午11:14
     */
    public function getList($condition) {
        try {
            return $this->where($condition)->order('ctime', 'desc')->paginate(20, false ,['query' => [
                'exam_topic_id' => $condition['exam_topic_id']
            ]]);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student_examtopic'),
                $e->getMessage(),
                $condition
            );
        }
        return false;

    }

    /**
     * 添加信息
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/27 下午11:14
     */
    public function addStudentExamTopic($data) {
        try {
            return $this->save($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student_examtopic'),
                $e->getMessage(),
                $data
            );
        }
        return false;
    }

    /**
     * 更新信息
     *
     * @param $condition
     * @param $data
     * @return bool|static
     * CreateTime: 2019/4/27 下午11:15
     */
    public function up($condition, $data) {
        try {
            return $this->where($condition)->update($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student_examtopic'),
                $e->getMessage(),
                [
                    'condition' => $condition,
                    'data' => $data
                ]
            );
        }
        return false;
    }

    /**
     * 统计已考完
     *
     * @return bool|mixed
     * CreateTime: 2019/4/27 下午11:15
     */
    public function countDeal() {

        try {
            $sql = "select exam_topic_id, status, count(*) as total from {$this->table} GROUP BY exam_topic_id,status";
            return $this->query($sql);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student_examtopic'),
                $e->getMessage()
            );
        }
        return false;
    }

    /**
     * 统计已通过信息
     *
     * @return bool|mixed
     * CreateTime: 2019/4/27 下午11:16
     */
    public function countPass() {
        try {
            $sql = "select exam_topic_id, count(*) as total from {$this->table} WHERE total_score>=60 GROUP BY exam_topic_id";
            return $this->query($sql);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student_examtopic'),
                $e->getMessage()
            );
        }
        return false;
    }

}