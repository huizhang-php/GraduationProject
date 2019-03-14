<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/6 下午7:28
 * Description:
 */

namespace app\common\model;

use think\Model;

class ExamPaperModel extends Model {

    protected $table='exam_paper';

    public static function instance() {
        return new ExamPaperModel();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:46
     * @param array $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function getList($condition=[]) {
        if (isset($condition['is_all']) && isset($condition['is_all']) == true) {
            return $this->order('ctime', 'desc')->select();
        }
        return $this->where($condition)->order('ctime', 'desc')->paginate(20);
    }

    public function add($data) {
        return $this->save($data);
    }

    public function up($condition, $data) {
        return $this->where($condition)->update($data);
    }

}
