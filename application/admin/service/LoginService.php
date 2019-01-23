<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午4:48

 * Description:
 */
namespace app\admin\service;
use app\common\model\AdminModel;

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
     */
    public function LoginService($data) {
        // 查看是否有此用户
        $condition = [
            'admin_name'    => $data['admin_name'],
            'admin_password'=> $data['admin_password'],
            'status'        => 1
        ];
        $adminInfo = $this->adminModel->findAdmin($condition);
        if (empty($adminInfo)) {
            return false;
        } else {
            // 存session
            session('admin_name',$data['admin_name']);
            session('admin_id',$data['admin_password']);
        }
        return true;
    }
}