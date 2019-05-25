<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/6 下午5:35
 * Description:
 */

namespace app\common\base;

use app\common\config\SelfConfig;
use app\common\tool\EsLog;
use think\Model;

class BaseModel extends Model {

    /**
     * 模块名称
     *
     * @var null
     * CreateTime: 2019/4/29 下午1:32
     */
    protected $modelName=null;

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午7:08
     * @var null
     * Description: 表名2
     */
    protected $table=null;

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午5:36
     * @param $condition
     * Description: 拼装sql条件
     * @return BaseModel|\think\db\Query
     */
    protected function getCond($condition, $table) {
        $this->table = $table;
        $myself = $this;
        $or = [];
        $order = [];
        $group = null;
        foreach ($condition as $key => $value) {
            if (is_array($value) && count($value)) {
                switch ($value[0]) {
                    case '<>':
                        $myself = $this->whereNotIn($key, $value[1]);
                        unset($condition[$key]);
                        break;
                    case 'or':
                        $or[$key] = $value[1];
                        unset($condition[$key]);
                        break;
                }
            }
            switch ($key) {
                case 'order':
                    $order = [$value[0],$value[1]];
                    unset($condition[$key]);
                    break;
                case 'group':
                    $group = $value;
                    unset($condition[$key]);
                    break;
            }
        }
        if (!empty($or)) {
           $myself = $myself->whereOr($or);
        }
        if (!empty($condition)){
            $myself = $myself->where($condition);
        }
        if (!empty($order)) {
            $myself = $myself->order($order[0],$order[1]);
        }
        if (!is_null($group)) {
            $myself = $myself->group($group);
        }
        return $myself;
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
            SelfConfig::getConfig('Log.modules.'.$this->modelName),
            $msg,
            $data
        );
    }
}