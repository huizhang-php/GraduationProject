<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/9 下午10:26
 * Description:
 */
namespace app\admin\controller;

use app\admin\service\StudentsService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;

class StudentsController extends BaseController implements ControllerInter {

    public function list() {
        $students = StudentsService::instance()->getList();
        $this->assign(
            ['students'=>$students]
        );
        return $this->fetch();
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
        $res = StudentsService::instance()->up_status($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }
}