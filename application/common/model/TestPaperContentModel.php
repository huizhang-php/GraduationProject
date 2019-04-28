<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  题库内容model层
 */

namespace app\common\model;

use app\common\base\BaseModel;
use app\common\config\SelfConfig;
use app\common\tool\EsLog;

class TestPaperContentModel extends BaseModel {

    /**
     * 表名
     *
     * @var string
     * CreateTime: 2019/4/28 下午11:11
     */
    protected $table = 'test_paper_content';

    /**
     * 返回当前实例
     *
     * @return TestPaperContentModel
     * CreateTime: 2019/4/28 下午11:11
     */
    public static function instance() {
        return new TestPaperContentModel();
    }

    /**
     * 批量添加
     *
     * @param $data
     * @return bool|\think\Collection
     * CreateTime: 2019/4/28 下午11:11
     */
    public function adds($data) {
        try {
            return $this->saveAll($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student'),
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
     * @return array|bool|mixed|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/28 下午11:13
     */
    public function getList($condition) {
        try {
            if (isset($condition['all'])) {
                unset($condition['all']);
                $res = $this->getCond($condition, $this->table)->select();
            } else {
                $res = $this->getCond($condition, $this->table)->order('ctime', 'desc')->paginate(20,false,['query' => [
                    'id' => $condition['exam_paper_id']
                ]]);
            }
            return $res;
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 随机查询
     *
     * @param $data
     * @return bool|mixed
     * CreateTime: 2019/4/28 下午11:13
     */
    public function randSelect($data) {
        try {
            $sql = "select * from test_paper_content where exam_paper_id={$data['question_bank_id']} and type={$data['type']} ORDER BY rand() limit 0,{$data['num']}";
            return $this->query($sql);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student'),
                $e->getMessage(),
                $data
            );
        }
        return false;
    }
}