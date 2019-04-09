<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午4:48

 * Description:
 */
namespace app\admin\service;
use app\common\model\AdminModel;
use app\common\model\FuncModel;
use app\common\model\RoleModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class LoginService {

    /**
     * User: yuzhao
     * CreateTime: 2019/1/6 下午4:51
     * @var

     * Description:
     */
    private $adminModel;

    /**
     * LoginService constructor.
     */
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public static function getObj() {
        return new LoginService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/6 下午5:02
     * @param $data
     * @return string
     * Description: 登录
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function LoginService($data) {
        // 查看是否有此用户
        $condition = [
            'admin_name'    => $data['admin_name'],
            'admin_password'=> md5($data['admin_password']),
            'status'        => 1
        ];
        $adminInfo = $this->adminModel->findAdmin($condition);
        // 查询角色
        $roleInfo = RoleModel::instance()->getList(['id'=>$adminInfo['role_id']]);
        $funcs = json_decode($roleInfo[0]['jurisdiction_id'], true);
        $funcIds = array_keys($funcs);
        foreach ($funcs as $key => $value) {
            foreach ($value as $key1 => $value2) {
                $funcIds[] = (int)$value2;
            }
        }
        // 查找功能信息
        $funcsInfo = FuncModel::instance()->getFunc(['id'=>$funcIds,'order'=>['id', 'asc']]);
        $funcsInfo = $funcsInfo->toArray();
        $newFuncs = [];
        foreach ($funcsInfo as $key => $value) {
            if ($value['pid'] === 0) {
                $newFuncs[$value['id']] = $value;
            } else {
                $newFuncs[$value['pid']]['two'][] = $value;
            }
        }
        if (empty($adminInfo)) {
            return false;
        } else {
            // 存session
            session('admin_name',$data['admin_name']);
            session('admin_id',$data['admin_password']);
            session('funcs',json_encode($newFuncs, JSON_UNESCAPED_UNICODE));
        }
        return true;
    }
}