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

class BaseController extends Controller {

    use MyRequest;
    use MyResponse;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->getParams();
    }

}