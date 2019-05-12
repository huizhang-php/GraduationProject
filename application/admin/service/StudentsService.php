<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  考生service层
 */
namespace app\admin\service;

use app\common\base\BaseModel;
use app\common\base\BaseService;
use app\common\base\ServiceInter;
use app\common\model\StudentsModel;
use app\common\tool\TimeTool;

class StudentsService extends BaseService implements ServiceInter {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:45
     */
    protected $modelName = 'student';

    /**
     * 返回当前实例
     *
     * @return StudentsService
     * CreateTime: 2019/4/29 下午2:45
     */
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

    /**
     * 更新考生状态
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:44
     */
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
        $this->wEsLog($result, $params);
        return false;
    }
}