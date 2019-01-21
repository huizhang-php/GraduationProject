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
    }

    public function del()
    {
        // TODO: Implement del() method.
    }

    public function up_status()
    {
        // TODO: Implement up_status() method.
    }
}