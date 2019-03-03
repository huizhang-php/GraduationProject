<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/31 下午3:52
 * Description:
 */
namespace app\admin\controller;

use app\admin\service\ExamTopicService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;

class ExamTopicController extends BaseController implements ControllerInter {

    public function list()
    {
        // TODO: Implement list() method.
        $list = ExamTopicService::instance()->getList();
        $this->assign([
            'list'  => $list
        ]);
        return view();
    }

    public function add()
    {
        // TODO: Implement add() method.
        $res = ExamTopicService::instance()->add($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function up()
    {
        // TODO: Implement up() method.
        $res = ExamTopicService::instance()->up($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function del()
    {
        // TODO: Implement del() method.
    }

    public function up_status()
    {
        // TODO: Implement up_status() method.
        $res = ExamTopicService::instance()->up_status($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }
}