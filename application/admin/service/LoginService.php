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
use app\common\tool\EsLog;

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
        if (!$adminInfo) {
            $this->wEsLog('没有此用户', $condition);
            return false;
        }
        // 查询角色
        $roleInfo = RoleModel::instance()->getList(['id'=>$adminInfo['role_id']])->toArray();
        if (!$adminInfo) {
            $this->wEsLog('角色查询失败', $data);
            return false;
        }
        if (strlen($roleInfo[0]['jurisdiction_id']) > 2) {
            $funcs = json_decode($roleInfo[0]['jurisdiction_id'], true);
            $funcIds = array_keys($funcs);
            foreach ($funcs as $key => $value) {
                foreach ($value as $key1 => $value2) {
                    $funcIds[] = (int)$value2;
                }
            }
            $cond = ['id'=>$funcIds,'order'=>['pid', 'asc']];
        } else {
            $cond = ['order'=>['pid', 'asc']];
        }
        // 查找功能信息
        $funcsInfo = FuncModel::instance()->getFunc($cond);
        $funcsInfo = $funcsInfo->toArray();
        $newFuncs = [];
        $twoFuncs = [];
        foreach ($funcsInfo as $key => $value) {
            if ($value['pid'] === 0) {
                $value['two'] = [];
                $newFuncs[$value['id']] = $value;
            } else {
                $twoFuncs[] = $value;
            }
        }
        foreach ($twoFuncs as $key => $value) {
            if (isset($newFuncs[$value['pid']])) {
                $newFuncs[$value['pid']]['two'][] = $value;
            }
        }
        // 存session
        session('admin_name',$data['admin_name']);
        session('admin_id',$data['admin_password']);
        session('funcs',json_encode($newFuncs, JSON_UNESCAPED_UNICODE));
        return true;
    }
}