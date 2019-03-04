<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/3 下午10:49
 * Description: 异步php
 */
namespace app\common\base;
use think\Controller;

class SynPhpBase extends Controller {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/4 下午10:39
     * @var array
     * Description: syn 过来的参数
     */
    protected $params;

    /**
     * SynPhpBase constructor.
     */
    public function __construct()
    {
        parent::__construct();
        global $argv;
        array_splice($argv, 0, 2);
        $this->params = $argv;
    }
}