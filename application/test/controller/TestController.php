<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/19 下午3:13
 * Description:
 */
namespace app\test\controller;

use app\common\tool\RabbitMQTool;
use think\Controller;

class TestController extends Controller {
    public function test() {
        RabbitMQTool::instance('test')->wMq(['name'=>'123']);
    }
}