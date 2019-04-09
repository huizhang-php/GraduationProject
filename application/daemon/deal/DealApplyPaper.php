<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/19 下午4:22
 * Description: 提交试卷处理
 */
namespace app\daemon\deal;

use app\common\model\ExamTopicModel;

class DealApplyPaper {

    /**
     * User: yuzhao
     * CreateTime: 2019/4/9 下午11:27
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function deal($data) {
        foreach ($data as $key => $value) {
            // 查找考试专题信息
            $examTopicInfo = ExamTopicModel::instance()->getList([$data['exam_topic_id']]);
            // 查找每个考生的考题

            // 查找每个考生的考题的详细信息

            // 开始阅卷

            // 更新考题
        }


    }
}