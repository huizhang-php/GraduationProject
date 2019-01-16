<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/11 下午5:32
 * Description: 角色管理
 */

namespace app\admin\controller;
use app\admin\service\RoleService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;
use app\admin\service\JurisdictionService;

class RoleController extends BaseController implements ControllerInter {

    public function list()
    {
        // TODO: Implement list() method.
        $jurisdictionService = new JurisdictionService();
        // 功能列表
        $funcList = $jurisdictionService->funcList();
        // 角色列表
        $roleList = RoleService::instance()->getList();
        $this->assign([
            'func_list' => $funcList,
            'role_list' => $roleList
        ]);
        return view();
    }

    public function add()
    {
        // TODO: Implement add() method.
        $res = RoleService::instance()->add($this->params, $result);
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