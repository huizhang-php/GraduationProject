<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午11:09
 * Description:
 */
namespace app\common\model;

use think\Model;

class RoleModel extends Model {

    protected $table='role';

    public static function instance() {
        return new RoleModel();
    }

    public function add($data) {
        return $this->save($data);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/4/9 下午9:56
     * @param $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function getList($condition=[]) {
        return $this->where($condition)->order('ctime', 'desc')->select();
    }

    public function up($condition, $data)
    {
        return $this->where($condition)->update($data);
    }

    public function del($condition) {
        return $this->where($condition)->delete();
    }
}