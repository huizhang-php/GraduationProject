<?php
/**
 * CreateTime: 2019/1/21 下午11:46
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  管理员service层
 */
namespace app\admin\service;

use app\common\base\BaseService;
use app\common\base\ServiceInter;
use app\common\tool\TimeTool;
use app\common\model\AdminModel;

class AdminService extends BaseService implements ServiceInter {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午1:33
     */
    protected $modelName = 'admin';

    /**
     * 返回当前对象
     *
     * @return AdminService
     * CreateTime: 2019/4/29 下午1:28
     */
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new AdminService();
    }

    /**
     * 获取列表
     *
     * @param array $params
     * @return array|bool|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/4/29 下午1:28
     */
    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        $adminModel = new AdminModel();
        return $adminModel->getList();
    }

    /**
     * 添加管理员
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午1:38
     */
    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        $adminModel = new AdminModel();
        $time = TimeTool::getTime();
        // 查找是否存在此账号
        $adminInfo = $adminModel->findAdmin(['admin_name'=>$params['admin_name']]);
        if (!empty($adminInfo)) {
            $result = '账号重复';
            $this->wEsLog('账号重复', $params);
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
        $this->wEsLog('添加管理员失败', $params);
        $result = '添加管理员失败';
        return false;
    }

    /**
     * 更新管理员
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午1:38
     */
    public function up($params=[], &$result)
    {
        // TODO: Implement up() method.
        $adminModel = new AdminModel();
        $time = TimeTool::getTime();
        // 查找是否存在此账号
        $adminInfo = $adminModel->findAdmin(['admin_name'=>$params['admin_name'],
            'id' => ['<>',$params['id']]
        ]);
        if (!empty($adminInfo)) {
            $result = '';
            $this->wEsLog('账号重复', $params);
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
        $this->wEsLog('修改管理员失败', $params);
        return false;

    }

    /**
     * 删除管理员
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午1:38
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
            $this->wEsLog('删除失败', $params);
            $result = '删除失败';
            return false;
        }
    }

    /**
     * 更新管理员状态
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午1:38
     */
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
        $this->wEsLog('状态修改失败', $params);
        $result = '状态修改失败';
        return false;
    }

    /**
     * 重置管理员密码
     *
     * @param $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午1:38
     */
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
        $this->wEsLog('重置密码失败', $params);
        $result = '重置密码失败';
        return false;
    }
}