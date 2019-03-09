<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/15 上午12:53
 * Description:
 */

namespace app\common\model;

use think\Model;

class TestPaperContentModel extends Model {
    protected $table = 'test_paper_content';

    public static function instance() {
        return new TestPaperContentModel();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/5 下午10:05
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     * Description:
     */
    public function adds($data) {
        return $this->saveAll($data);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/5 下午10:05
     * @param $condition
     * @return \think\Paginator
     * @throws \think\exception\DbException
     * Description:
     */
    public function getList($condition) {
        return $this->where($condition)->order('ctime', 'desc')->paginate(20,false,['query' => [
            'id' => $condition['exam_paper_id']
        ]]);
    }
}