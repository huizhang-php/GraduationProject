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

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:04
     * @param array $params
     * @param $result
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        // 查看专题名称是否重复
        $res = ExamTopicModel::instance()->getList(['name'=>$params['name']]);
        if (!empty($res->toArray()['data'])) {
            $result = '专题名称重复';
            return false;
        }
        $data = [
            'name' => $params['name'],
            'introduction' => $params['introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name'),
            'question_bank_config' => json_encode($params['question_bank'], JSON_UNESCAPED_UNICODE),
            'test_paper_type' => $params['test_paper_type'],
            'test_start_time' => $params['test_start_time'],
            'test_time_length' => $params['test_time_length'],
            'question_bank_id' => $params['question_bank_id']
        ];
        $res = ExamTopicModel::instance()->save($data);
        if ($res) {
            $result = '添加专题成功';
            return true;
        }
        $result = '添加专题失败';
        return false;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 上午12:45
     * @param array $params
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        return ExamTopicModel::instance()->getList($params);
    }

    public function up($params=[], &$result)
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

    public function del($params=[], &$result)
    {
        // TODO: Implement del() method.
    }

    public function up_status($params=[], &$result)
    {
        // TODO: Implement up_status() method.
        // 查看试卷是否准备就绪
        $condition['id'] = $params['id'];
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