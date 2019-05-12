<?php
/**
 * @CreateTime:   2019/5/5 下午10:26
 * @Author:       yuzhao  <yuzhao@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  模块日志
 */
namespace app\admin\controller;
use app\admin\service\ModelLogService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;

class ModelLogController extends BaseController implements ControllerInter {

    public function list()
    {
        // TODO: Implement list() method.
        $searchConf = ModelLogService::instance()->getSearchConf();
        $this->assign($searchConf);
        return view();
    }

    public function add()
    {
        // TODO: Implement add() method.
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

    /**
     * 获取日志信息
     *
     * CreateTime: 2019/5/6 上午11:42
     */
    public function get_log_data() {
        $res = ModelLogService::instance()->getLogData($this->params);
        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }
}