<?php
/**
 * @CreateTime:   2019/4/15 下午11:50
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  统计相关操作
 */
namespace app\admin\service;

use app\common\base\ServiceInter;
use app\common\model\EveryStudentTopicModel;
use app\common\model\ExamTopicModel;
use app\common\model\StudentExamTopicModel;

class CountService implements ServiceInter {

    public static function instance()
    {
        // TODO: Implement instance() method.
        return new CountService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/4/15 下午11:58
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * Description: 获取考试专题统计信息
     */
    public function getExamTopicCountInfo() {
        // 获取今年和去年的数据格式
        $nowY = date('Y',time());
        $lastY = date('Y',strtotime('-1 year'));
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $returnNowY = [];
        $returnLastY = [];
        foreach ($months as $key => $value) {
            $returnNowY[$nowY.$value] = 0;
            $returnLastY[$lastY.$value] = 0;
        }
        // 本年考试专题各月份考试统计
        $nowYear = ExamTopicModel::instance()->monthCount();
        foreach ($nowYear as $key => $value) {
            if (array_key_exists($value['name'], $returnNowY)) {
                $returnNowY[$value['name']] = $value['total'];
            }
        }
        // 去年考试专题各月份考试统计
        $lastYear = ExamTopicModel::instance()->monthCount(1);
        foreach ($lastYear as $key => $value) {
            if (array_key_exists($value['name'], $returnNowY)) {
                $returnLastY[$value['name']] = $value['total'];
            }
        }
        return [
            'year' => [$nowY, $lastY],
            'title' => array_keys($returnNowY),
            'now_value' => array_values($returnNowY),
            'last_value' => array_values($returnLastY),
        ];
    }

    public function examTopicCount() {
        $countData = [];
        $res = StudentExamTopicModel::instance()->countDeal();
        foreach ($res as $key => $value) {
            switch ($value['status']) {
                case 0:
                    $countData[$value['exam_topic_id']]['no_participate'] = $value['total'];
                    break;
                case 1:

                case 2:
                    $countData[$value['exam_topic_id']]['participate_people'] = $value['total'];
                    break;
            }
            if (isset($countData[$value['exam_topic_id']]['total'])) {
                $countData[$value['exam_topic_id']]['total'] += $value['total'];
            } else {
                $countData[$value['exam_topic_id']]['total'] = $value['total'];
            }
        }
        $res = StudentExamTopicModel::instance()->countPass();
        foreach ($res as $key => $value) {
            $countData[$value['exam_topic_id']]['pass_people'] = round($value['total']/$value['exam_topic_id']['total']*100,2)."％";
        }
        $res = ExamTopicModel::instance()->getList(['id'=>array_keys($countData)], false);
        foreach ($res as $key => $value) {
            $countData[$value['id']]['name'] = $value['name'];
        }
        foreach ($countData as $key => $value) {
            if (!isset($value['no_participate'])) {
                $countData[$key]['no_participate'] = 0;
            }
            if (!isset($value['name'])) {
                $countData[$key]['name'] = '';
            }
            if (!isset($value['total'])) {
                $countData[$key]['total'] = 0;
            }
            if (!isset($value['participate_people'])) {
                $countData[$key]['participate_people'] = 0;
                $countData[$key]['participate'] = round($countData[$key]['participate_people']/$countData[$key]['total']*100,2)."％";
            } else {
                $countData[$key]['participate'] = round($countData[$key]['participate_people']/$countData[$key]['total']*100,2)."％";
            }
            if (!isset($value['pass_people'])) {
                $countData[$key]['pass_people'] = 0;
                $countData[$key]['pass'] = "0%";
            } else {
                $countData[$key]['people'] = round($countData[$key]['people']/$countData[$key]['total']*100,2)."％";
            }
        }
        return $countData;
    }

    public function getList($params = [])
    {
        // TODO: Implement getList() method.
    }

    public function add($params = [], &$result)
    {
        // TODO: Implement add() method.
    }

    public function up($params = [], &$result)
    {
        // TODO: Implement up() method.
    }

    public function del($params = [], &$result)
    {
        // TODO: Implement del() method.
    }

    public function up_status($params = [], &$result)
    {
        // TODO: Implement up_status() method.
    }
}