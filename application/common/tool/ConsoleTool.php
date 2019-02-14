<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/15 上午12:05
 * Description:
 */
namespace app\common\tool;

class ConsoleTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/15 上午12:06
     * @param $data
     * Description: 格式化输出
     */
    public static function console($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}