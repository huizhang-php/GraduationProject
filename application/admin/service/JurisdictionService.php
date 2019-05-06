<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  系统功能service层
 */
namespace app\admin\service;

use app\common\base\BaseService;
use app\common\model\FuncModel;
use app\common\tool\TimeTool;

class JurisdictionService extends BaseService {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:33
     */
    protected $modelName = 'func';

    /**
     * 功能模型层
     *
     * @var $funcModel FuncModel
     * CreateTime: 2019/4/29 下午2:33
     */
    private $funcModel;

    /**
     * JurisdictionService constructor.
     */
    public function __construct()
    {
        $this->funcModel = new FuncModel();
    }

    /**
     * 获取当前对象
     *
     * @return JurisdictionService
     * CreateTime: 2019/4/29 下午2:33
     */
    public static function getObj() {
        return new JurisdictionService();
    }

    /**
     * 功能列表
     *
     * @return array
     * CreateTime: 2019/4/29 下午2:34
     */
    public function funcList() {
        $funcList = $this->funcModel->getFunc();
        $oneFunc = [];
        $twoFunc = [];
        foreach ($funcList as $key => $value) {
            if ($value['pid'] > 0) {
                $twoFunc[] = $value;
            } else {
                $oneFunc[$value['id']]['data'] = $value;
            }
        }
        foreach ($twoFunc as $key => $value) {
            if (!isset($oneFunc[$value['pid']])) continue;
            $oneFunc[$value['pid']]['twoData'][] = $value;
        }
        return $oneFunc;
    }

    /**
     * 添加功能
     *
     * @param $data
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:34
     */
    public function addFunc($data,&$result) {
        $condition['func_name'] = $data['func_name'];
        // 判断重复
        if (!empty($this->funcModel->find($condition))) {
            $result =  '功能名称重复';
            $this->wEsLog($result, $condition);
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
        $this->wEsLog($result, $addData);
        return false;
    }

    /**
     * 更新状态
     *
     * @param $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:34
     */
    public function upStatus($params, &$result) {
        $idType = explode('-',$params['id_type']);
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
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
        $this->wEsLog($result, [
            'condition' => $condition,
            'data' => $data
        ]);
        return false;
    }

    /**
     * 是否存在二级功能
     *
     * @param $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:34
     */
    public function isHaveTwoFunc($params, &$result) {
        $condition['pid'] = $params['id'];
        $res = $this->funcModel->find($condition);
        if (!empty($res)) {
            $result = '有子功能不能删除';
            return false;
        } else {
            $result = '无子功能';
            return true;
        }
    }

    /**
     * 删除功能
     *
     * @param $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:34
     */
    public function delFunc($params, &$result) {
        $condition['id'] = $params['id'];
        $res = $this->funcModel->delFunc($condition);
        if ($res) {
            $result = '删除功能成功';
            return true;
        } else {
            $result = '删除功能失败';
            $this->wEsLog($result, $params);
            return false;
        }
    }

    /**
     * 更新功能
     *
     * @param $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:34
     */
    public function upFunc($params, &$result) {
        $condition['id'] = $params['id'];
        unset($params['id']);
        $data = $params;
        $data['end_staff'] = session('admin_name');
        if ($this->funcModel->upFunc($condition, $data)) {
            $result = '修改成功';
            return true;
        }
        $result = '修改失败';
        $this->wEsLog($result, $params);
        return false;
    }

}
