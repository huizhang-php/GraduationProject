<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  每个学生的考题model层
 */
namespace app\common\model;

use app\common\base\BaseModel;
use app\common\config\SelfConfig;
use app\common\tool\EsLog;

class EveryStudentTopicModel extends BaseModel {

    /**
     * 每个学生的考题表
     *
     * @var string
     * CreateTime: 2019/4/28 下午10:45
     */
    protected $table = 'every_student_topic';

    /**
     * 返回当前实例
     *
     * @return EveryStudentTopicModel
     * CreateTime: 2019/4/28 下午10:45
     */
    public static function instance() {
        return new EveryStudentTopicModel();
    }

    /**
     * 和试卷内容一对一关系
     *
     * @return \think\model\relation\HasOne
     * CreateTime: 2019/4/28 下午10:45
     */
    public function testpaperinfo() {
        return $this->hasOne('TestPaperContentModel','id', 'test_paper_content_id');
    }

    /**
     * 添加考题
     *
     * @param $data
     * @return bool|\think\Collection
     * CreateTime: 2019/4/28 下午10:49
     */
    public function addTopic($data) {
        try {
            return $this->saveAll($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.every_student_topic'),
                $e->getMessage(),
                $data
            );
        }
        return false;
    }

    /**
     * 查找
     *
     * @param $condition
     * @return array|bool|mixed|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/4/28 下午10:51
     */
    public function getList($condition) {
        try {
            return $this->getCond($condition, $this->table)->select();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.every_student_topic'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 删除
     *
     * @param $condition
     * @return bool|int
     * CreateTime: 2019/4/28 下午10:52
     */
    public function del($condition) {
        try {
            return $this->where($condition)->delete();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.every_student_topic'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 更新
     *
     * @param $condition
     * @param $data
     * @return bool|int|string
     * CreateTime: 2019/4/28 下午10:52
     */
    public function up($condition,$data) {
        try {
            return $this->where($condition)->update($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.every_student_topic'),
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
     * 批量更新
     *
     * @param $data
     * @return bool|\think\Collection
     * CreateTime: 2019/4/28 下午10:53
     */
    public function upAll($data) {
        try {
            return $this->saveAll($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.every_student_topic'),
                $e->getMessage(),
                $data
            );
        }
        return false;
    }

    public function countScore($studentExamTopicIds) {
        $ids = join(',', $studentExamTopicIds);
        $sql = "select sum(score) as total,student_exam_topic_id from {$this->table} WHERE student_exam_topic_id in({$ids}) GROUP BY student_exam_topic_id";
        return $this->query($sql);
    }

}