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

    /**
     * User: yuzhao
     * CreateTime: 2019/1/8 下午10:49
     * @var FuncModel $funcModel
     * Description:
     */
    private $funcModel;

    public function __construct()
    {
        $this->funcModel = new FuncModel();
    }

    public static function getObj() {
        return new JurisdictionService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/1/8 下午10:47
     * Description: 功能列表
     */
    public function funcList() {
        $funcList = $this->funcModel->getFunc();
        return $funcList;
    }

    public function addFunc($data,&$result) {
        $condition['func_name'] = $data['func_name'];
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
            'ctime' => $time,
            'utime' => $time,
            'func_introduction' => $data['func_introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name')
        ];
        if ($data['type'] != 0) {
            $addData['pid'] = $data['pid'];
        }
        if ($this->funcModel->addFunc($addData)) {
            $result = '添加功能成功';
            return true;
        }
        $result = '添加功能失败';
        return false;
    }

    public function upStatus($params, &$result) {
        $idType = explode('-',$params['id_type']);
        $data['status'] = $params['status'];
        if (count($idType) == 2) {
            $condition = "pid={$idType[0]} or id={$idType[0]}";
        } else {
            $condition['id'] = $idType[0];
        }
        if ($this->funcModel->upFunc($condition, $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        return false;
    }
}