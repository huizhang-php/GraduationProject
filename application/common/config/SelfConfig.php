<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/7 下午10:39
 * Description: 获取自定义配置
 */

namespace app\common\config;

class SelfConfig {

    public static function getConfig($confName) {
        $confParams = explode('.', $confName);
        $config = include_once $confParams[0] . '.php';
        return $config[$confParams[1]];
    }

}