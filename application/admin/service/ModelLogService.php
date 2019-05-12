<?php
/**
 * @CreateTime:   2019/5/5 下午10:48
 * @Author:       yuzhao  <yuzhao@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  模块日志service层
 */
namespace app\admin\service;

use app\common\base\BaseService;
use app\common\base\ServiceInter;
use app\common\config\SelfConfig;
use app\common\tool\Es;
use app\common\tool\EsLog;

class ModelLogService extends BaseService implements ServiceInter {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/5/6 上午11:11
     */
    protected $modelName = 'model_log';

    /**
     * 返回当前实例
     *
     * @return ModelLogService
     * CreateTime: 2019/5/5 下午10:53
     */
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new ModelLogService();
    }

    /**
     * 获取搜索配置
     *
     * CreateTime: 2019/5/5 下午10:54
     */
    public function getSearchConf() {
        $logConf = SelfConfig::getConfig('Log');;
        return [
            'model_names' => $logConf['model_name'],
            'log_type' => $logConf['log_type'],
        ];
    }

    /**
     * 获取日志信息
     *
     * CreateTime: 2019/5/6 上午10:43
     * @param array $data
     * @return bool | array
     */
    public function getLogData(array $data = []) {
        // es 查询
        $page = $data['page']-1;
        $limit = $data['limit'];
        $params = [
            'index' => 'es_log_*',
            'type' => 'doc',
            'sort' => 'ctime',
            'from' => $page*$limit, 'size' => $limit
        ];
        $must = array();
        $filter = array();
        if (isset($data['time'])) {
            $time = explode(' - ', $data['time']);
            if (count($time) === 2) {
                $filter[] = array('range'=>array('ctime'=>array('gte'=>$time[0],'lte'=>$time[1])));
            }
        }
        if (isset($data['log_type']) && $data['log_type'] != 0) {
            $must[] = array('term'=>array('type'=>$data['log_type']));
        }
        if (isset($data['model_name']) && $data['model_name'] != 0) {
            $must[] = array('term'=>array('action'=>$data['model_name']));
        }
        $body['query']['bool']['must'] = $must;
        $body['query']['bool']['filter'] = $filter;
        $params['body'] = $body;
        $logData = Es::instance()->readEs($params);
        if ($logData === false) {
            EsLog::wLog(EsLog::ERROR, $this->modelName, '查询es日志失败', $params);
            return false;
        }
        // 获取日志配置
        $logConf = SelfConfig::getConfig('Log');
        $logType = $logConf['log_type'];
        $logModelName = $logConf['model_name'];
        $newData = [];
        // 格式化查询出来的log
        foreach ($logData['data'] as $val) {
            $val = $val['_source'];
            $oneNewData = [];
            if (isset($logModelName[$val['type']])) {
                $oneNewData['type'] = $logModelName[$val['type']];
            } else {
                $oneNewData['type'] = $val['type'];
            }
            if (isset($logType[$val['action']])) {
                $oneNewData['action'] = $logType[$val['action']];
            } else {
                $oneNewData['action'] = $val['action'];
            }
            $oneNewData['data'] = $val['data'];
            $oneNewData['ctime'] = $val['ctime'];
            $oneNewData['msg'] = $val['msg'];
            $newData[] = $oneNewData;
        }
        $returnData = [
            'code' => 0,
            'count' => $logData['total'],
            'data' => $newData,
        ];
        return $returnData;
    }

    public function getList($params = [])
    {
        // TODO: Implement getList() method.
    }

    public function add($params = [], &$result)
    {
        // TODO: Implement add() method.
    }

    public function up($params = [], &$result)
    {
        // TODO: Implement up() method.
    }

    public function del($params = [], &$result)
    {
        // TODO: Implement del() method.
    }

    public function up_status($params = [], &$result)
    {
        // TODO: Implement up_status() method.
    }
}