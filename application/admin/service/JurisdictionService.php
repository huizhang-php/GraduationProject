<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午11:28

 * Description:
 */

namespace app\admin\service;

use app\common\model\FuncModel;
use app\common\tool\TimeTool;

class JurisdictionService {

    private $funcModel;

    public function __construct()
    {
        $this->funcModel = new FuncModel();
    }

    public static function getObj() {
        return new JurisdictionService();
    }

    public function addFunc($data,&$result) {
        $condition['func_name'] = $data['func_name'];
        if ($data['type'] != 0 && $data['pid'] == 0) {
            $result =  '请选择一级菜单';
            return false;
        }
        // 判断重复
        if (!empty($this->funcModel->find($condition))) {
            $result =  '功能名称重复';
            return false;
        }
        $time = TimeTool::getTime();
        // 添加
        $addData = [
            'func_name' => $data['func_name'],
            'status' => $data['status'],
            'func_url' => $data['func_url'],
            'pid' => $data['pid'],
            'ctime' => $time,
            'utime' => $time,
            'func_introduction' => $data['func_introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name')
        ];
        if ($this->funcModel->addFunc($addData)) {
            $result = '添加功能成功';
            return true;
        }
        $result = '添加功能失败';
        return false;
    }
}