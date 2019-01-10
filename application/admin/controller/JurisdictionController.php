<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午9:12

 * Description:
 */
namespace app\admin\controller;
use app\common\base\MyRequest;
use app\common\base\MyResponse;
use think\Controller;
use app\admin\service\JurisdictionService;
class JurisdictionController extends Controller {
    use MyRequest;
    use MyResponse;

    public function func_list() {
        $jurisdictionService = new JurisdictionService();
        $funcList = $jurisdictionService->funcList();
        $oneFunc = [];
        $twoFunc = [];
        foreach ($funcList as $key => $value) {
            if ($value['pid'] > 0) {
                $twoFunc[] = $value;
            } else {
                $oneFunc[$value['id']]['data'] = $value;
            }
        }
        foreach ($twoFunc as $key => $value) {
            $oneFunc[$value['pid']]['twoData'][] = $value;
        }
        $this->assign(['oneFunc'  => $oneFunc]);
        return view();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/8 下午10:45
     * Description: 添加功能
     */
    public function add_func() {
        $jurisdictionService = new JurisdictionService();
        $params = $this->getParams();
        $res = $jurisdictionService->addFunc($params,$result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function up_status() {
        $jurisdictionService = new JurisdictionService();
        $params = $this->getParams();
        $res = $jurisdictionService->upStatus($params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

}