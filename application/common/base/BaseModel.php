<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/6 下午5:35
 * Description:
 */

namespace app\common\base;

use think\Model;

class BaseModel extends Model {

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
     */
    protected function getCond($condition, $table) {
        $this->table = $table;
        $myself = $this;
        $or = [];
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
        }
        if (!empty($or)) {
           $myself = $myself->whereOr($or);
        }
        if (!empty($condition)){
            $myself = $myself->where($condition);
        }
        return $myself;
    }
}