<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/3 下午10:49
 * Description: 异步php
 */
namespace app\common\base;
use think\Controller;

class SynPhpBase extends Controller {

    protected $params;

    public function __construct()
    {
        parent::__construct();
        global $argv;
        $this->params = $argv;
        unset($this->params[0],$this->params[1]);
    }
}