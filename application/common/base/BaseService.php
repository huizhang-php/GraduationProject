<?php
/**
 * @CreateTime:   2019/4/29 下午1:31
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  base service 层
 */
namespace app\common\base;

use app\common\config\SelfConfig;
use app\common\tool\EsLog;

class BaseService {

    /**
     * 模块名称
     *
     * @var null
     * CreateTime: 2019/4/29 下午1:32
     */
    protected $modelName=null;

    /**
     * BaseService constructor.
     */
    public function __construct()
    {

    }

    /**
     * 写es日志
     *
     * @param $msg
     * @param $data
     * CreateTime: 2019/4/29 下午1:35
     */
    protected function wEsLog($msg, $data, $type=EsLog::ERROR) {
        EsLog::wLog($type,
            SelfConfig::getConfig('log.modules.'.$this->modelName),
            $msg,
            $data
        );
    }
}