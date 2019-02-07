<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/21 下午11:46
 * Description:
 */
namespace app\admin\service;

use app\common\base\ServiceInter;
use app\common\tool\TimeTool;
use app\common\model\AdminModel;

class AdminService implements ServiceInter {

    public static function instance()
    {
        // TODO: Implement instance() method.
        return new AdminService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:38
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 获取列表
     */
    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        $adminModel = new AdminModel();
        return $adminModel->getList();
    }

    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        $adminModel = new AdminModel();
        $time = TimeTool::getTime();
        // 查找是否存在此账号
        $adminInfo = $adminModel->findAdmin(['admin_name'=>$params['admin_name']]);
        if (!empty($adminInfo)) {
            $result = '账号重复';
            return false;
        }
        $params['admin_password'] = md5(123456);
        $params['ctime'] = $time;
        $params['utime'] = $time;
        $params['staff'] = session('admin_name');
        $params['end_staff'] = session('admin_name');
        $res = $adminModel->add($params);
        if ($res) {
            $result = '添加管理员成功';
            return true;
        }
        $result = '添加管理员失败';
        return false;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:38
     * @param $params
     * @param $result
     * Description: 更新
     * @return bool
     */
    public function up($params=[], &$result)
    {
        // TODO: Implement up() method.
        $adminModel = new AdminModel();
        $time = TimeTool::getTime();
        // 查找是否存在此账号
        $adminInfo = $adminModel->findAdmin(['admin_name'=>$params['admin_name']]);
        if (!empty($adminInfo)) {
            $result = '账号重复';
            return false;
        }
        $condition['id'] = $params['id'];
        unset($params['id']);
        $params['utime'] = $time;
        $params['end_staff'] = session('admin_name');
        $res = $adminModel->up($condition, $params);
        if ($res) {
            $result = '修改管理员成功';
            return true;
        }
        $result = '修改管理员失败';
        return false;

    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:35
     * @param $params
     * @param $result
     * @return bool
     * @throws \Exception
     * Description: 删除管理员
     */
    public function del($params=[], &$result)
    {
        // TODO: Implement del() method.
        $adminModel = new AdminModel();
        $res = $adminModel->del($params);
        if ($res) {
            $result = '删除成功';
            return true;
        } else {
            $result = '删除失败';
            return false;
        }
    }

    public function up_status($params=[], &$result)
    {
        // TODO: Implement up_status() method.
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
        $condition['id'] = $params['id'];
        $adminModel = new AdminModel();
        if ($adminModel->up($condition, $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        return false;
    }

    public function resetPassword($params, &$result) {
        $adminModel = new AdminModel();
        $res = $adminModel->up(['id'=>$params['id']], [
            'admin_password'=>md5('123456'),
            'utime' => TimeTool::getTime()
        ]);
        if ($res) {
            $result = '重置密码成功';
            return true;
        }
        $result = '重置密码失败';
        return false;
    }
}