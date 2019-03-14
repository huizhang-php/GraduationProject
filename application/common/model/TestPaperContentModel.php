<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/15 上午12:53
 * Description:
 */

namespace app\common\model;


use app\common\base\BaseModel;

class TestPaperContentModel extends BaseModel {
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
        if (isset($condition['all'])) {
            unset($condition['all']);
            $res = $this->getCond($condition, $this->table)->select();
        } else {
            $res = $this->getCond($condition, $this->table)->order('ctime', 'desc')->paginate(20,false,['query' => [
                'id' => $condition['exam_paper_id']
            ]]);
        }
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/14 下午10:41
     * @param $data
     * @return mixed
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * Description:
     */
    public function randSelect($data) {
        $sql = "select * from test_paper_content where exam_paper_id={$data['question_bank_id']} and type={$data['type']} ORDER BY rand() limit 0,{$data['num']}";
        return $this->query($sql);
    }
}