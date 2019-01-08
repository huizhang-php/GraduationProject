<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/5 下午7:04

 * Description:
 */
namespace app\admin\controller;
use app\common\base\MyRequest;
use app\common\base\MyResponse;
use think\Controller;
use think\facade\Request;
use app\admin\service\LoginService;
class LoginController extends Controller {

    use MyRequest;
    use MyResponse;

    /**
     * User: yuzhao
     * CreateTime: 2019/1/5 下午7:05
     * @return \think\response\View

     * Description: 登录
     */
    public function login() {
        if (Request::isPost()) {
            $params = $this->getParams();
            if(!captcha_check($params['captcha'])){
                $this->returnAjax(400,'验证码错误');
            };
            $loginResult = LoginService::getObj()->LoginService($params);
            if ($loginResult) {
                $this->returnAjax(200,'登录成功');
            } else {
                $this->returnAjax(400, '登录失败');
            }
        }
        return view();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/6 下午9:00

     * Description: 退出系统
     */
    public function unlogin() {
        session(null);
        $this->redirect('login');
    }

}

