<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/6 下午7:24
 * Description:
 */
namespace app\admin\controller;

use app\admin\service\ExamPaperService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;
use app\common\config\SelfConfig;

class ExamPaperController extends BaseController implements ControllerInter {

    public function list()
    {
        // TODO: Implement list() method.
        $list = ExamPaperService::instance()->getList();
        $this->assign([
            'list'  => $list
        ]);
        return view();
    }

    public function add()
    {
        // TODO: Implement add() method.
        $res = ExamPaperService::instance()->add($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function up()
    {
        // TODO: Implement up() method.
        $res = ExamPaperService::instance()->up($this->params, $result);
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
        $res = ExamPaperService::instance()->up_status($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    public function issue() {
        $res = ExamPaperService::instance()->getList($this->params);
        $this->assign([
            'exam_paper_base_info' => $res[0],
            'exam_type_base_conf' => SelfConfig::getConfig('Exam.exam_type_base_conf')
        ]);
        return view();
    }

    public function get_exam_type_base_conf() {
        $this->returnAjax(200, 'success', SelfConfig::getConfig('Exam.exam_type_base_conf'));
    }
}