<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/19 下午3:13
 * Description:
 */
namespace app\test\controller;

use app\common\tool\EsLog;
use app\common\tool\RabbitMQTool;
use app\common\tool\Es;
use think\Controller;

class TestController extends Controller {
    public function wmq() {
        RabbitMQTool::instance('test')->wMq(['name'=>'123']);
    }

    public function es() {
        EsLog::wLog();
    }
}