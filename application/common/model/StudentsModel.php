<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  学生model层
 */
namespace app\common\model;

use app\common\base\BaseModel;
use app\common\config\SelfConfig;
use app\common\tool\EsLog;

class StudentsModel extends BaseModel {

    /**
     * 学生表
     *
     * @var string
     * CreateTime: 2019/4/28 下午11:07
     */
    protected $table='students';

    /**
     * 返回当前实例
     *
     * @return StudentsModel
     * CreateTime: 2019/4/28 下午11:08
     */
    public static function instance() {
        return new StudentsModel();
    }

    /**
     * 查找
     *
     * @param array $data
     * @return bool|\think\Paginator
     * CreateTime: 2019/4/28 下午11:08
     */
    public function getList($data=[]) {
        try {
            $res = $this->getCond($data, $this->table)->order('ctime', 'desc')->paginate(20);
            return $res;
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
     * 发现学生
     *
     * @param $condition
     * @return array|bool|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/5/13 下午5:17
     */
    public function findStudent($condition) {
        try {
            $res = $this->where($condition)->select()->toArray();
            if (empty($res)) {
                return false;
            }
            return $res[0];
        } catch (\Throwable $e) {
            console($e->getMessage());
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student_examtopic'),
                $e->getMessage(),
                $condition
            );
        }
        return false;

    }

    /**
     * 添加学生
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/28 下午11:09
     */
    public function addStudent($data) {
        try {
            return $this->save($data);
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
     * 更新学生信息
     *
     * @param $condition
     * @param $data
     * @return bool|int|string
     * CreateTime: 2019/4/28 下午11:10
     */
    public function upStudent($condition, $data) {
        try {
            $res = $this->where($condition)->update($data);
            return $res;
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.student'),
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