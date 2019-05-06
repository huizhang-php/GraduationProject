<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  登录service层
 */
namespace app\admin\service;
use app\common\base\BaseService;
use app\common\model\AdminModel;
use app\common\model\FuncModel;
use app\common\model\RoleModel;

class LoginService extends BaseService {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:37
     */
    protected $modelName = 'login';

    /**
     * 管理员model层
     *
     * @var AdminModel
     * CreateTime: 2019/4/29 下午2:37
     */
    private $adminModel;

    /**
     * LoginService constructor.
     */
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * 获取当前对象
     *
     * @return LoginService
     * CreateTime: 2019/4/29 下午2:37
     */
    public static function getObj() {
        return new LoginService();
    }

    /**
     * 登录
     *
     * @param $data
     * @return bool
     * CreateTime: 2019/4/29 下午2:37
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