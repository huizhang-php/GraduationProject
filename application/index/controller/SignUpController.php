<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/6 下午10:52
 * Description:
 */
namespace app\index\controller;

use app\common\base\BaseController;
use app\index\service\SignUpService;

class SignUpController extends BaseController {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:53
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 报名页面
     */
    public function sign_up_view() {
        $examTopicInfo = SignUpService::instance()->signUpView($this->params['id']);
        $this->assign([
                'exam_topic_info' => $examTopicInfo,
                'title' => $examTopicInfo['name'].'-在线报名！',
                'exam_topic_id' => $this->params['id']
            ]
        );
        return $this->fetch();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午6:09
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 注册
     */
    public function sign_up() {
        $res = SignUpService::instance()->signUp($this->params,$result);
        if ($res) {
            $this->returnAjax(200,$result);
        }
        $this->returnAjax(400,$result);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/8 下午12:51
     * Description: 发送短信
     */
    public function send_sms() {

        $res = SignUpService::instance()->sendSms($this->params, $result);
        if ($res === false) {
            $this->returnAjax(400,$result);
        }
        $this->returnAjax(200,$result);
    }

    public function apply() {
        $res = SignUpService::instance()->apply($this->params, $result);
        if ($res === false) {
            $this->returnAjax(400,$result);
        }
        $this->returnAjax(200,$result);
    }

    public function confirm_sign_up() {
        SignUpService::instance()->confirmSignUp($this->params, $result);
        $this->assign([
            'result' => $result
        ]);
        return $this->fetch();
    }

}
