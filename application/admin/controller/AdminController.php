<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/21 下午11:21
 * Description: 管理员控制器
 */
namespace app\admin\controller;
use app\admin\service\AdminService;
use app\admin\service\RoleService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;

class AdminController extends BaseController implements ControllerInter {

    public function list()
    {
        // TODO: Implement list() method.
        // 获取角色列表
        $roleList = RoleService::instance()->getList(['status'=>1]);
        // 管理员列表
        $adminList = AdminService::instance()->getList();
        $this->assign([
            'roleList'  => $roleList,
            'adminList' => $adminList
        ]);
        return view();
    }

    public function add()
    {
        // TODO: Implement add() method.
        $res = AdminService::instance()->add($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function up()
    {
        // TODO: Implement up() method.
        $res = AdminService::instance()->up($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:38
     * Description: 重置密码
     */
    public function reset_password() {
        $res = AdminService::instance()->resetPassword($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/23 下午11:36
     * @throws \Exception
     * Description: 删除管理员
     */
    public function del()
    {
        // TODO: Implement del() method.
        $res = AdminService::instance()->del($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function up_status()
    {
        // TODO: Implement up_status() method.
        $res = AdminService::instance()->up_status($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }
}