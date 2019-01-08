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
        return view();
    }

    public function add_func() {
        $jurisdictionService = new JurisdictionService();
        $params = $this->getParams();
        $res = $jurisdictionService->addFunc($params,$result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

}