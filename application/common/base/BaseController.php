<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午2:41
 * Description:
 */
namespace app\common\base;
use think\Controller;
use app\common\base\MyRequest;
use app\common\base\MyResponse;
use think\facade\Session;

class BaseController extends Controller {

    use MyRequest;
    use MyResponse;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->isSession();
        $this->getParams();
        $this->assign(['title' => '在线考试系统']);
    }

    public function isSession() {
        if (!Session::has('admin_name')) {
            session(null);
            $this->redirect('admin/login/login');
        }
    }

}