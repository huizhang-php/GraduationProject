<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/7 下午10:39
 * Description: 获取自定义配置
 */

namespace app\common\config;

class SelfConfig {

    /**
     * 获取配置信息
     *
     * @param $confName
     * @return mixed
     * CreateTime: 2019/5/6 上午10:17
     */
    public static function getConfig($confName) {
        $confParams = explode('.', $confName);
        $config = include $confParams[0] . '.php';
        $confData = $config;
        unset($confParams[0]);
        foreach ($confParams as $key => $val) {
            $confData = $confData[$val];
        }
        return $confData;
    }

}