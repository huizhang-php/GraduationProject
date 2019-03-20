<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/14 下午11:12
 * Description:
 */
namespace app\common\model;

use app\common\base\BaseModel;

class EveryStudentTopicModel extends BaseModel {

    protected $table = 'every_student_topic';

    public static function instance() {
        return new EveryStudentTopicModel();
    }

    public function testpaperinfo() {
        return $this->hasOne('TestPaperContentModel','id', 'test_paper_content_id');
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 上午12:31
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     * Description:
     */
    public function addTopic($data) {
        $res = $this->saveAll($data);
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/19 下午11:03
     * @param $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function getList($condition) {
        return $this->getCond($condition, $this->table)->select();
    }

    public function del($condition) {
        return $this->delete($condition);
    }

    public function up($condition,$data) {
        return $this->where($condition)->update($data);
    }
}