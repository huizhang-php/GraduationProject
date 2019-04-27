<?php
/**
 * @CreateTime:   2019/4/27 下午10:05
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  角色model层
 */
namespace app\common\model;

use app\common\config\SelfConfig;
use app\common\tool\EsLog;
use think\Model;

class RoleModel extends Model {

    /**
     * 角色表
     *
     * @var string
     * CreateTime: 2019/4/27 下午11:08
     */
    protected $table='role';

    /**
     * 返回当前实例
     *
     * @return RoleModel
     * CreateTime: 2019/4/27 下午11:08
     */
    public static function instance() {
        return new RoleModel();
    }

    /**
     * 添加角色
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/27 下午11:09
     */
    public function add($data) {
        try {
            return $this->save($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.admin'),
                $e->getMessage(),
                $data
            );
        }
        return false;
    }

    /**
     * 获取角色信息
     *
     * @param array $condition
     * @return array|bool|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/4/27 下午11:10
     */
    public function getList($condition=[]) {
        try {
            return $this->where($condition)->order('ctime', 'desc')->select();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.admin'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }

    /**
     * 更新角色信息
     *
     * @param $condition
     * @param $data
     * @return bool|static
     * CreateTime: 2019/4/27 下午11:10
     */
    public function up($condition, $data)
    {
        try {
            return $this->where($condition)->update($data);
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.admin'),
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
     * 删除角色
     *
     * @param $condition
     * @return bool
     * CreateTime: 2019/4/27 下午11:11
     */
    public function del($condition) {
        try {
            return $this->where($condition)->delete();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.admin'),
                $e->getMessage(),
                $condition
            );
        }
        return false;
    }
}