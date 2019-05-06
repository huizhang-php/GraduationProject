<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  考生service层
 */

namespace app\admin\service;
use app\common\base\BaseService;
use app\common\base\ServiceInter;
use app\common\tool\TimeTool;
use app\common\model\RoleModel;

class RoleService extends BaseService implements ServiceInter {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:44
     */
    protected $modelName = 'role';
    
    /**
     * 返回当前实例
     *
     * @return RoleService
     * CreateTime: 2019/4/29 下午2:42
     */
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new RoleService();
    }

    /**
     * 查找
     *
     * @param array $params
     * @return array|bool|\PDOStatement|string|\think\Collection
     * CreateTime: 2019/4/29 下午2:42
     */
    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        $roleModel = new RoleModel();
        return $roleModel->getList();
    }

    /**
     * 添加
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:43
     */
    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        $roleModel = new RoleModel();
        $time = TimeTool::getTime();
        $data = [
            'jurisdiction_id' => json_encode($params['two_func'], JSON_UNESCAPED_UNICODE),
            'status'    => $params['status'],
            'role_name' => $params['role_name'],
            'role_introduction' => $params['role_introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name'),
            'ctime' => $time,
            'utime' => $time
        ];
        $res = $roleModel->save($data);
        if ($res) {
            $result = '添加角色成功';
            return true;
        }
        $result = '添加角色失败';
        $this->wEsLog($result, $params);
        return false;
    }

    /**
     * 更新
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:43
     */
    public function up($params=[], &$result)
    {
        // TODO: Implement up() method.
        $roleModel = new RoleModel();
        $condition['id'] = $params['id'];
        $params['jurisdiction_id'] = json_encode($params['jurisdiction_id']);
        unset($params['id']);
        $data = $params;
        $res = $roleModel->up($condition, $data);
        if ($res) {
            $result = '修改成功';
            return true;
        } else {
            $result = '修改失败';
            $this->wEsLog($result, $params);
            return false;
        }
    }

    /**
     * 删除
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:43
     */
    public function del($params=[], &$result)
    {
        // TODO: Implement del() method.
        $roleModel = new RoleModel();
        $res = $roleModel->del($params);
        if ($res) {
            $result = '删除成功';
            return true;
        } else {
            $result = '删除失败';
            $this->wEsLog($result, $params);
            return false;
        }
    }

    /**
     * 更新状态
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:43
     */
    public function up_status($params=[], &$result)
    {
        // TODO: Implement up_status() method.
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
        $condition['id'] = $params['id'];
        $roleModel = new RoleModel();
        if ($roleModel->up($condition, $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        $this->wEsLog($result, $params);
        return false;
    }
}