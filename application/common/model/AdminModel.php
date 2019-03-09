<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午4:52

 * Description:
 */
namespace app\common\model;
use think\Model;
class AdminModel extends Model {

    protected $table = 'admin';

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:46
     * Description: 一对多关联
     */
    public function adminrole() {
        return $this->hasOne('RoleModel', 'id', 'role_id');
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:35
     * @param $condition
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 查找
     */
    public function findAdmin($condition) {
        return $this->where($condition)->find();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:35
     * @param $data
     * @return bool|false|int
     * Description: 添加
     */
    public function add($data) {
        return $this->save($data);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:35
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 获取列表
     */
    public function getList() {
        return $this->order('ctime', 'desc')->select();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:35
     * @param $condition
     * @return bool|int
     * @throws \Exception
     * Description:
     */
    public function del($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:40
     * @param $condition
     * @param $data
     * @return static
     * Description: 更新
     */
    public function up($condition, $data) {
        return $this->where($condition)->update($data);
    }
}