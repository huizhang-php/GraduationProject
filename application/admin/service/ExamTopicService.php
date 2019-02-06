<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/11 下午5:34
 * Description:
 */

namespace app\admin\service;
use app\common\base\ServiceInter;
use app\common\model\ExamTopicModel;
use app\common\tool\TimeTool;

class ExamTopicService implements ServiceInter {

    public static function instance()
    {
        // TODO: Implement instance() method.
        return new ExamTopicService();
    }

    public function add($params, &$result)
    {
        // TODO: Implement add() method.
        // 查看专题名称是否重复
        $res = ExamTopicModel::instance()->getList(['name'=>$params['name']]);
        if (!empty($res)) {
            $result = '专题名称重复';
            return false;
        }
        $data = [
            'name' => $params['name'],
            'introduction' => $params['introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name'),
        ];
        $res = ExamTopicModel::instance()->save($data);
        if ($res) {
            $result = '添加专题成功';
            return true;
        }
        $result = '添加专题失败';
        return false;
    }

    public function getList()
    {
        // TODO: Implement getList() method.
        return ExamTopicModel::instance()->getList([]);
    }

    public function up($params, &$result)
    {
        // TODO: Implement up() method.
        $condition['id'] = $params['id'];
        $params['introduction'] = json_encode($params['introduction']);
        unset($params['id']);
        $data = $params;
        $res = ExamTopicModel::instance()->up($condition, $data);
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
    }

    public function up_status($params, &$result)
    {
        // TODO: Implement up_status() method.
        // 查看试卷是否准备就绪
        $condition['id'] = $params['id'];
        $res = ExamTopicModel::instance()->getList($condition);
        if (empty($res)) {
            $result = '状态修改失败';
            return false;
        }
        if ($res[0]['test_paper_status'] == 0) {
            $result = '还没有添加试卷';
            return false;
        }
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
        $data['utime'] = TimeTool::getTime();
        if (ExamTopicModel::instance()->up($condition, $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        return false;
    }
}