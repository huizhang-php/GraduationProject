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
    public function getList($condition, bool $isPage=true) {
        $myself = $this->getCond($condition, $this->table)->order('ctime', 'desc');
        if ($isPage) {
            return $myself->paginate(20);
        }
        return $myself->select();
    }

    public function up($condition, $data) {
        $res = $this->where($condition)->update($data);
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/4/15 下午11:57
     * @param $year
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * Description:
     */
    public function monthCount($year=0) {
        $sql = "select DATE_FORMAT(test_start_time,'%Y%m') as name,count(*) as total from {$this->table}";
        if ($year != 0) {
            $startTime = date('Y-01-01',strtotime($year . ' year'));
            $endTime = date('Y-12-31',strtotime('-' . $year . ' year'));
            $sql .= " where test_start_time>={$startTime} and test_start_time<={$endTime} ";
        }
        $sql .= " group by test_start_time";
        $res = $this->query($sql);
        return $res;
    }

}