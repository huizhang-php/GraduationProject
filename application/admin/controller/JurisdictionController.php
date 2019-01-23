<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午9:12

 * Description:
 */
namespace app\admin\controller;

use app\common\base\BaseController;
use app\admin\service\JurisdictionService;
use app\common\base\ControllerInter;

class JurisdictionController extends BaseController implements ControllerInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/1/11 下午5:24
     * @return \think\response\View
     * Description: 功能列表
     */
    public function list() {
        // TODO: Implement list() method.
        $jurisdictionService = new JurisdictionService();
        $funcList = $jurisdictionService->funcList();
        $this->assign(['oneFunc'  => $funcList]);
        return view();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/8 下午10:45
     * Description: 添加功能
     */
    public function add() {
        // TODO: Implement add() method.
        $jurisdictionService = new JurisdictionService();
        $res = $jurisdictionService->addFunc($this->params,$result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/11 下午5:24
     * Description: 修改状态
     */
    public function up_status() {
        // TODO: Implement up_status() method.
        $jurisdictionService = new JurisdictionService();
        $res = $jurisdictionService->upStatus($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/11 下午5:24
     * Description: 判断是否有二级功能
     */
    public function is_hava_two_func() {
        $jurisdictionService = new JurisdictionService();
        $res = $jurisdictionService->isHaveTwoFunc($this->params, $result);
        if ($res) {
            $this->returnAjax(200, $result);
        }
        $this->returnAjax(400, $result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/11 下午5:24
     * Description: 删除功能
     */
    public function del() {
        // TODO: Implement del() method.
        $jurisdictionService = new JurisdictionService();
        $res = $jurisdictionService->delFunc($this->params, $result);
        if ($res) {
            $this->returnAjax(200, $result);
        }
        $this->returnAjax(400, $result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/11 下午5:25
     * Description: 更新功能
     */
    public function up() {
        // TODO: Implement up() method.
        $jurisdictionService = new JurisdictionService();
        $res = $jurisdictionService->upFunc($this->params, $result);
        if ($res) {
            $this->returnAjax(200, $result);
        }
        $this->returnAjax(400, $result);
    }

}