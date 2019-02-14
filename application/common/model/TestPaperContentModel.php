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

    public function adds($data) {
        return $this->saveAll($data);
    }
}