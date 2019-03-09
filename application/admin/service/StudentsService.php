<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/9 下午10:59
 * Description:
 */
namespace app\admin\service;

use app\common\base\BaseModel;
use app\common\base\ServiceInter;
use app\common\model\StudentsModel;
use app\common\tool\TimeTool;

class StudentsService extends BaseModel implements ServiceInter {


    public static function instance()
    {
        // TODO: Implement instance() method.
        return new StudentsService();
    }

    public function getList($params = [])
    {
        // TODO: Implement getList() method.
        return StudentsModel::instance()->getList();
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
        $res = StudentsModel::instance()->upStudent(['id'=>$params['id']],[
            'status'=>$params['status'],
            'utime' => TimeTool::getTime(),
            'end_staff' => session('admin_name')
        ]);
        if ($res) {
            $result = '更改状态成功';
            return true;
        }
        $result = '更改状态失败';
        return false;
    }
}