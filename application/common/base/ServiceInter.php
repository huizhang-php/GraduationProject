<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/14 下午7:31
 * Description:
 */

namespace app\common\base;

interface ServiceInter {

    public static function instance();

    public function getList();

    public function add($params, &$result);

    public function up($params, &$result);

    public function del($params, &$result);

    public function up_status($params, &$result);

}