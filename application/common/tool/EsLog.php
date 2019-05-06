<?php
/**
 * @CreateTime:   2019/4/27 下午4:11
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  es 日志收集
 */
namespace app\common\tool;
use app\common\tool\Es;

class EsLog {

    // 错误
    const ERROR = ERROR;
    // 调试
    const DEBUG = DEBUG;
    // 业务
    const BUSINESS = BUSINESS;

    /**
     * 记录es日志
     *
     * CreateTime: 2019/4/27 下午5:41
     * @param $type
     * @param $action
     * @param $msg
     * @param $data
     */
    public static function wLog(string $type=self::ERROR, string $action = ERROR, string $msg='', array $data=[]) {
        $body = [
            'type' => $type,
            'action' => $action,
            'msg' => $msg,
            'ctime' => TimeTool::getTime(),
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ];
        $params = [
            'index' => 'es_log_'.date('Ymd'),
            'type' => 'doc',
            'id' => time(),
            'body' => $body
        ];
        Es::instance()->writeEs($params);
    }

}
