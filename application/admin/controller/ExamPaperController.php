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
use app\common\tool\ShellTool;

class ExamPaperController extends BaseController implements ControllerInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/19 下午10:27
     * @return \think\response\View
     * Description:
     */
    public function list()
    {
        // TODO: Implement list() method.
        $list = ExamPaperService::instance()->getList();
        $this->assign([
            'list'  => $list
        ]);
        return view();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/19 下午10:27
     * Description:
     */
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

    /**
     * User: yuzhao
     * CreateTime: 2019/2/17 下午8:23
     * @return \think\response\View
     * Description: 获取展示信息
     */
    public function issue() {
        $res = ExamPaperService::instance()->getList($this->params);
        // 获取对应试卷信息
        $testPaperInfo = ExamPaperService::instance()->getTestPaperInfo($this->params['id']);
        $this->assign([
            'exam_paper_base_info' => $res[0],
            'exam_type_base_conf' => SelfConfig::getConfig('Exam.exam_type_base_conf'),
            'test_paper_info' => $testPaperInfo
        ]);
        return view();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/17 下午8:23
     * Description: 获取题类型配置信息
     */
    public function get_exam_type_base_conf() {
        $this->returnAjax(200, 'success', SelfConfig::getConfig('Exam.exam_type_base_conf'));
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/17 下午8:23
     * Description: 保存试卷信息
     */
    public function save_test_paper() {
        $res = ExamPaperService::instance()->saveTestPaper($this->params, $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/2 下午7:47
     * Description: 导入题库
     */
    public function upload_file() {
        $res = $this->getFile('exam_paper');
        if ($res==false) {
            $this->returnAjax(400, '上传文件失败');
        }
        // 目前只支持单文件
        $res = ExamPaperService::instance()->uploadFile($res[0], $this->params['id'], $result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }
}