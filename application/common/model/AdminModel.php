<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  管理员model层
 */
namespace app\common\model;
use app\common\base\BaseModel;
use app\common\config\SelfConfig;
use app\common\tool\EsLog;
use think\Model;
class AdminModel extends BaseModel {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/5/15 下午3:27
     */
    protected $modelName = 'admin';

    protected $table = 'admin';

    /**
     * 角色和管理员一对一关联
     *
     * @return \think\model\relation\HasOne
     * CreateTime: 2019/4/27 下午11:00
     */
    public function adminrole() {
        return $this->hasOne('RoleModel', 'id', 'role_id');
    }

    /**
     * 查找管理员
     *
     * @param $condition
     * @return array|bool|null|\PDOStatement|string|Model
     * CreateTime: 2019/4/27 下午11:00
     */
    public function findAdmin($condition) {
        try {
            $res = $this->getCond($condition, $this->table)->find();
            return $res;
        } catch (\Throwable $e) {
            $this->wEsLog('查找管理员失败', [$e->getMessage(),$condition]);
;        }
        return false;
    }

    /**
     * 添加管理员
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/27 下午11:00
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
     * 获取管理员列表
     *
     * @return array|bool|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/4/27 下午11:01
     */
    public function getList() {
        try {
            return $this->order('ctime', 'desc')->select();
        } catch (\Throwable $e) {
            EsLog::wLog(EsLog::ERROR,
                SelfConfig::getConfig('log.modules.admin'),
                $e->getMessage()
            );
        }
        return false;
    }

    /**
     * 删除管理员
     *
     * @param $condition
     * @return bool
     * CreateTime: 2019/4/27 下午11:01
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

    /**
     * 更新管理员信息
     *
     * @param $condition
     * @param $data
     * @return bool|static
     * CreateTime: 2019/4/27 下午11:01
     */
    public function up($condition, $data) {
        try {
            return $this->getCond($condition,$this->table)->update($data);
        } catch (\Throwable $e) {
            $this->wEsLog('更新管理员信息失败', [
                $data,$e->getMessage()
            ]);
        }
        return false;
    }
}