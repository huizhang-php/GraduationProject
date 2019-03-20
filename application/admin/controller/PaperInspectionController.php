<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/19 下午10:18
 * Description:
 */
namespace app\admin\controller;

use app\admin\service\PaperInspectionService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;

class PaperInspectionController extends BaseController implements ControllerInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/19 下午10:36
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function list()
    {
        // TODO: Implement list() method.
        $examTopicInfo = PaperInspectionService::instance()->getList();
        $this->assign(['list'=>$examTopicInfo]);
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
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/19 下午11:26
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function paper_inspection() {
        $students = PaperInspectionService::instance()->paperInspection($this->params);
        $this->assign($students);
        return $this->fetch();
    }

}