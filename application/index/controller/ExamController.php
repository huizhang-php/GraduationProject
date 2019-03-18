<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/10 下午1:12
 * Description:
 */
namespace app\index\controller;

use app\common\base\BaseController;
use app\index\service\ExamService;

class ExamController extends BaseController {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/11 下午10:12
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 考试验证页面
     */
    public function exam() {
        $res = ExamService::instance()->exam($this->params, $msg);
        if ($res === false) {
            $this->assign(['msg'=>$msg]);
        } else {
            $this->assign($res);
        }
        return $this->fetch();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/14 下午6:00
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     * @throws \Exception
     */
    public function test_view() {
        $res = ExamService::instance()->testView($this->params, $msg);
        if ($res === false) {
            die($msg);
        }
        $this->assign($res);
        return $this->fetch();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/18 下午5:02
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function test_apply() {
        ExamService::instance()->testApply($this->params, $msg);
    }

}