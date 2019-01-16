<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午2:45
 * Description: 控制器公共方法类
 */

namespace app\common\base;

interface ControllerInter {

    public function list();

    public function add();

    public function up();

    public function del();

    public function up_status();

}