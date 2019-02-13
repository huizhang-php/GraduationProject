<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/6 下午7:25
 * Description:
 */

namespace app\admin\service;
use app\common\base\ServiceInter;
use app\common\model\ExamPaperModel;
use app\common\tool\TimeTool;

class ExamPaperService implements ServiceInter
{
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new ExamPaperService();
    }

    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        return ExamPaperModel::instance()->getList($params);
    }

    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        // 查看专题名称是否重复
        $res = ExamPaperModel::instance()->getList(['name'=>$params['name']]);
        if (!empty($res->toArray())) {
            $result = '试卷名称重复';
            return false;
        }
        $data = [
            'name' => $params['name'],
            'introduction' => $params['introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name'),
        ];
        $res = ExamPaperModel::instance()->add($data);
        if ($res) {
            $result = '添加试卷成功';
            return true;
        }
        $result = '添加试卷失败';
        return false;
    }

    public function up($params=[], &$result)
    {
        // TODO: Implement up() method.
        $condition['id'] = $params['id'];
        unset($params['id']);
        $params['end_staff'] = session('admin_name');
        $params['utime'] = TimeTool::getTime();
        $res = ExamPaperModel::instance()->up($condition, $params);
        if ($res) {
            $result = '修改成功';
            return true;
        } else {
            $result = '修改失败';
            return false;
        }
    }

    public function del($params=[], &$result)
    {
        // TODO: Implement del() method.
    }

    public function up_status($params=[], &$result)
    {
        // TODO: Implement up_status() method.
        // 查看是否出题
        $condition['id'] = $params['id'];
        $res = ExamPaperModel::instance()->getList($condition);
        if (empty($res->toArray())) {
            $result = '状态修改失败';
            return false;
        }
        if ($res[0]['exam_paper_id'] == 0) {
            $result = '还没有出题';
            return false;
        }
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
        $data['utime'] = TimeTool::getTime();
        if (ExamPaperModel::instance()->up(['id'=>$params['id']], $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        return false;
    }

    public function saveTestPaper($params=[], &$result) {
        var_dump($params);die;
    }

}