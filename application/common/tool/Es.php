<?php
/**
 * @CreateTime:   2019/4/27 下午4:11
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  es 工具
 */

namespace app\common\tool;

use app\common\config\SelfConfig;
use Elasticsearch\ClientBuilder;

class Es {

    /**
     * es 连接对象
     *
     * @var $es \Elasticsearch\Client|null
     * CreateTime: 2019/4/27 下午4:16
     */
    private static $esConn = null;

    /**
     * 当前对象
     *
     * @var
     * CreateTime: 2019/4/27 下午4:25
     */
    private static $es = null;

    public function __construct()
    {
        $conf = SelfConfig::getConfig('Source.es');
        self::$esConn = ClientBuilder::create()->setHosts([
            $conf['host'].':'.$conf['port']
        ])->build();
    }

    /**
     * 返回当前对象
     *
     * @return Es|\Elasticsearch\Client|null
     * CreateTime: 2019/4/27 下午4:26
     */
    public static function instance() {
        if (is_null(self::$es)) {
            self::$es = new Es();
        }
        return self::$es;
    }

    /**
     * 写es
     *
     * @param $params
     * CreateTime: 2019/4/27 下午4:30
     * @return bool
     */
    public function writeEs(array $params = []) {
        $res = self::$esConn->index($params);
        if (isset($res['_shards']['successful']) === 1) {
            return true;
        }
        return false;
    }

    /**
     * 查找数据
     *
     * @param array $params
     * CreateTime: 2019/5/6 上午10:46
     */
    public function readEs(array $params = []) {
        $res = self::$esConn->search($params);
        if (isset($res['hits']['hits'])) {
            return [
                'data' => $res['hits']['hits'],
                'total' => $res['hits']['total']
            ];
        }
        return false;
    }
}

