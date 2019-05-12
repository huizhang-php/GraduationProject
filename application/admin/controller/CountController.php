<?php
/**
 * @CreateTime:   2019/4/14 下午10:59
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  统计控制器
 */
namespace app\admin\controller;
use app\admin\service\CountService;
use app\common\base\BaseController;
use app\common\base\ControllerInter;

class CountController extends BaseController implements ControllerInter {

    public function list()
    {
        // TODO: Implement list() method.
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
     * CreateTime: 2019/4/15 下午11:58
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * Description:
     */
    public function get_count_data() {
       $res = CountService::instance()->getExamTopicCountInfo();
       $this->returnAjax(200, '获取数据成功', $res);
    }

    public function exam_topic_count_view() {
        $res = CountService::instance()->examTopicCount();
        $this->assign(['list' => $res]);
        return $this->fetch();
    }

}