<?php
/**
 * @CreateTime:   2019/5/5 下午4:07
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  index 模块base
 */
namespace app\common\base;
use app\common\base\MyRequest;
use app\common\base\MyResponse;
use think\Controller;

class BaseIndexController extends Controller{
    use MyRequest;
    use MyResponse;

    protected $funcs = [];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->getParams();
    }
}

