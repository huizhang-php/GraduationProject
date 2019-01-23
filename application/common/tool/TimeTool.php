<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午11:46

 * Description:
 */
namespace app\common\tool;
class TimeTool {

    public static function getTime($format='Y-m-d H:i:s') {
        return date($format,time());
    }
}