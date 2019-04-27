<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  功能model层
 */

namespace app\common\model;

use app\common\base\BaseModel;
use app\common\config\SelfConfig;
use app\common\tool\EsLog;

class FuncModel extends BaseModel {

    /**
     * 表名
     *
     * @var string
     * CreateTime: 2019/4/27 下午11:04
     */
    protected $table = 'func';

    /**
     * 返回当前实例
     *
     * @return FuncModel
     * CreateTime: 2019/4/27 下午11:04
     */
    public static function instance() {
        return new FuncModel();
    }

    /**
     * 查找系统功能
     *
     * @param $condition
     * @return array|bool|null|\PDOStatement|string|\think\Model
     * CreateTime: 2019/4/27 下午11:04
     */
    public function findFunc($condition) {
        try {
            return $this->where($condition)->find();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.func'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 添加系统功能
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/27 下午11:05
     */
    public function addFunc($data) {
        try {
            return $this->save($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.func'),
                $e->getMessage(),
                $data
            );
        }
        return false;
    }

    /**
     * 查找系统功能
     *
     * @param array $condition
     * @return array|bool|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/4/27 下午11:06
     */
    public function getFunc($condition=[]) {
        try {
            return $this->getCond($condition, $this->table)->select();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.func'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 更新系统功能
     *
     * @param $condition
     * @param $data
     * @return bool|static
     * CreateTime: 2019/4/27 下午11:06
     */
    public function upFunc($condition,$data) {
        try {
            return $this->where($condition)->update($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.func'),
                $e->getMessage(),
                [
                    'condition' => $condition,
                    'data' => $data
                ]
            );
        }
        return false;
    }

    /**
     * 删除系统功能
     *
     * @param $condition
     * @return bool
     * CreateTime: 2019/4/27 下午11:06
     */
    public function delFunc($condition) {
        try {
            return $this->where($condition)->delete();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.func'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }
}