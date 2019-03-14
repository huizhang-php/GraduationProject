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

    public function getList($condition) {
        return $this->where($condition)->select();
    }

    public function del($condition) {
        return $this->delete($condition);
    }
}