<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/11 下午5:34
 * Description:
 */

namespace app\admin\service;
use app\common\base\ServiceInter;
use app\common\tool\TimeTool;
use app\common\model\RoleModel;

class RoleService implements ServiceInter {

    public static function instance()
    {
        // TODO: Implement instance() method.
        return new RoleService();
    }

    public function getList()
    {
        // TODO: Implement getList() method.
        $roleModel = new RoleModel();
        return $roleModel->getList();
    }

    public function add($params, &$result)
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
        return false;
    }

    public function up($params, &$result)
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
            return false;
        }
    }

    public function del($params, &$result)
    {
        // TODO: Implement del() method.
        $roleModel = new RoleModel();
        $res = $roleModel->del($params);
        if ($res) {
            $result = '删除成功';
            return true;
        } else {
            $result = '删除失败';
            return false;
        }
    }

    public function up_status($params, &$result)
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
        return false;
    }
}