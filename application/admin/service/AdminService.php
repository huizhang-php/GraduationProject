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

    public function getList()
    {
        // TODO: Implement getList() method.
        $adminModel = new AdminModel();
        return $adminModel->getList();
    }

    public function add($params, &$result)
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

    public function up($params, &$result)
    {
        // TODO: Implement up() method.
    }

    public function del($params, &$result)
    {
        // TODO: Implement del() method.
    }

    public function up_status($params, &$result)
    {
        // TODO: Implement up_status() method.
    }
}