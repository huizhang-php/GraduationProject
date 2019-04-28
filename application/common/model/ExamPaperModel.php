<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  题库基本model层
 */
namespace app\common\model;

use app\common\config\SelfConfig;
use app\common\tool\EsLog;
use think\Model;

class ExamPaperModel extends Model {

    /**
     * 题库基本表
     *
     * @var string
     * CreateTime: 2019/4/28 下午10:55
     */
    protected $table='exam_paper';

    /**
     * 返回当前对象
     *
     * @return ExamPaperModel
     * CreateTime: 2019/4/28 下午10:55
     */
    public static function instance() {
        return new ExamPaperModel();
    }

    /**
     * 查找
     *
     * @param array $condition
     * @return array|bool|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/28 下午10:56
     */
    public function getList($condition=[]) {
        try {
            if (isset($condition['is_all']) && isset($condition['is_all']) == true) {
                return $this->order('ctime', 'desc')->select();
            }
            return $this->where($condition)->order('ctime', 'desc')->paginate(20);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_paper'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 添加
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/28 下午10:57
     */
    public function add($data) {
        try {
            return $this->save($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_paper'),
                $e->getMessage(),
                $data
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
     * CreateTime: 2019/4/28 下午10:57
     */
    public function up($condition, $data) {
        try {
            return $this->where($condition)->update($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.exam_paper'),
                $e->getMessage(),
                [
                    'condition' => $condition,
                    'data' => $data
                ]
            );
        }
        return false;
    }

}
