<?php
/**
 * @CreateTime:   2019/4/15 下午11:50
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  统计相关操作
 */
namespace app\admin\service;

use app\common\base\ServiceInter;
use app\common\model\ExamTopicModel;

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