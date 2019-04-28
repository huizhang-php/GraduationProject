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

class ExamTopicModel extends BaseModel {

    /**
     * 考试专题表
     *
     * @var string
     * CreateTime: 2019/4/28 下午10:59
     */
    protected $table='exam_topic';

    /**
     * 题库一对一关系
     *
     * @return \think\model\relation\HasOne
     * CreateTime: 2019/4/28 下午11:00
     */
    public function examtopicquestionbank() {
        return $this->hasOne('ExamPaperModel', 'id', 'question_bank_id');
    }

    /**
     * 返回当前对象
     *
     * @return ExamTopicModel
     * CreateTime: 2019/4/28 下午11:00
     */
    public static function instance() {
        return new ExamTopicModel();
    }

    /**
     * 添加考试专题
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/28 下午11:00
     */
    public function add($data) {
        try {
            return $this->save($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_topic'),
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
     * @param bool $isPage
     * @return array|bool|mixed|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/28 下午11:04
     */
    public function getList($condition, bool $isPage=true) {
        try {
            $myself = $this->getCond($condition, $this->table)->order('ctime', 'desc');
            if ($isPage) {
                return $myself->paginate(20);
            }
            return $myself->select();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_topic'),
                $e->getMessage(),
                [
                    'condition' => $condition,
                    'is_pape' => $isPage
                ]
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
     * CreateTime: 2019/4/28 下午11:05
     */
    public function up($condition, $data) {
        try {
            $res = $this->where($condition)->update($data);
            return $res;
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_topic'),
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
     * 按月统计
     *
     * @param int $year
     * @return mixed
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * CreateTime: 2019/4/28 下午11:05
     */
    public function monthCount($year=0) {
        try {
            $sql = "select DATE_FORMAT(test_start_time,'%Y%m') as name,count(*) as total from {$this->table}";
            if ($year != 0) {
                $startTime = date('Y-01-01',strtotime($year . ' year'));
                $endTime = date('Y-12-31',strtotime('-' . $year . ' year'));
                $sql .= " where test_start_time>={$startTime} and test_start_time<={$endTime} ";
            }
            $sql .= " group by test_start_time";
            $res = $this->query($sql);
            return $res;
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_topic'),
                $e->getMessage(),
                $year
            );
        }
        return false;
    }

}