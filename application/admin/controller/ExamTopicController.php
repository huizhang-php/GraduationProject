<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/31 下午3:52
 * Description:
 */
namespace app\admin\controller;

use app\admin\service\ExamPaperService;
use app\admin\service\ExamTopicService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;
use app\common\config\SelfConfig;

class ExamTopicController extends BaseController implements ControllerInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:47
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function list()
    {
        // TODO: Implement list() method.
        // 获取题类型
        $list = ExamTopicService::instance()->getList();
        $this->assign([
            'list'  => $list
        ]);
        return $this->fetch();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:47
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function add_view() {
        $total = 0;
        $examType = SelfConfig::getConfig('Exam.exam_type_base_conf');
        foreach ($examType as $key => $value) {
            $total += $value['score'] * $value['number'];
        }
        // 获取题库
        $questionBankList = ExamPaperService::instance()->getList(['is_all'=>true]);
        $this->assign([
            'exam_type' => $examType,
            'total' => $total,
            'question_bank_list' => $questionBankList
        ]);
        return view();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:40
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
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