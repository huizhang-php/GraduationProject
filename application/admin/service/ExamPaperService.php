<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/6 下午7:25
 * Description:
 */

namespace app\admin\service;
use app\common\base\ServiceInter;
use app\common\model\ExamPaperModel;
use app\common\model\TestPaperContentModel;
use app\common\tool\ConsoleTool;
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
        $bigTitles = $params['big_title'];
        $addBigTitleData = [];
        $examPaperId = $params['exam_paper_id'];
        unset($params['big_title'], $params['exam_paper_id']);
        // 将大标题拼装入库
        foreach ($bigTitles as $key => $bigTitle) {
            $bigTitleInfo = explode(',', $bigTitle);
            $addBigTitleData[] = [
                'big_title' => $bigTitleInfo[0],
                'first_n'   => $key +1,
                'type'  => $bigTitleInfo[1],
                'exam_paper_id' => $examPaperId
            ];
        }
        if (!TestPaperContentModel::instance()->adds($addBigTitleData)) {
            $result = '批量插入大标题失败';
            return false;
        }
        $data = [];
        $newData = [];
        // 处理入库数据
        foreach ($params as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $topicInfo = explode(',', $key1);
                if (!isset($data[$topicInfo[0]][$topicInfo[1]])) {
                    $data[$topicInfo[0]][$topicInfo[1]] = [
                        'exam_paper_id' => $examPaperId,
                        'first_n' => $topicInfo[$topicInfo[1]]
                    ];
                }
                foreach ($value1 as $key2 => $value2) {
                    switch ($key) {
                        case 'title':
                            $data[$topicInfo[0]][$topicInfo[1]]['title'] = $value2;
                            break;
                        case 'score':
                            $data[$topicInfo[0]][$topicInfo[1]]['score'] = $value2;
                            break;
                        case 'right_key':
                            $data[$topicInfo[0]][$topicInfo[1]]['right_key'] = $value2;
                            break;
                        case 'a':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['a'] = $value2;
                            break;
                        case 'b':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['b'] = $value2;
                            break;
                        case 'c':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['c'] = $value2;
                            break;
                        case 'd':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['d'] = $value2;
                            break;
                    }
                }
            }
        }
        // 将option转为json
        foreach ($data as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (isset($value1['option'])) {
                    $value[$key1]['option'] = json_encode($value1['option'], JSON_UNESCAPED_UNICODE);
                }
            }
            $newData = array_merge($newData, $value);
        }
        if (!TestPaperContentModel::instance()->adds($newData)) {
            $result = '批量插入小题失败';
            return false;
        }
        $result = '保存成功';
        return true;
    }

}