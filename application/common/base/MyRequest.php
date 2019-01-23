<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午3:56

 * Description:
 */
namespace app\common\base;
use think\facade\Request;

trait MyRequest {

    public $params = array();

    public function getParams() {
        $this->params = Request::post();
    }

}