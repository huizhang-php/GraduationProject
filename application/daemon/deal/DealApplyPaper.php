<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/19 下午4:22
 * Description: 提交试卷处理
 */
namespace app\daemon\deal;

use app\common\model\EveryStudentTopicModel;
use app\common\model\ExamTopicModel;
use app\common\model\StudentExamTopicModel;
use app\common\model\TestPaperContentModel;

class DealApplyPaper {

    /**
     * User: yuzhao
     * CreateTime: 2019/4/9 下午11:27
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 自动阅卷处理
     */
    public function deal($data) {
        foreach ($data as $key => $value) {
            // 查找考试专题信息
            $examTopicInfo = ExamTopicModel::instance()->getList(['id' => $value['exam_topic_id']]);
            $examTopicInfo = $examTopicInfo->toArray();
            $examTopicInfo = $examTopicInfo['data'][0];
            $everyStudentTopicCond = ['student_exam_topic_id'=>$value['student_exam_topic_id']];
            $topics = EveryStudentTopicModel::instance()->getList($everyStudentTopicCond)->toArray();
            $newTopics = [];
            $answer = [];
            foreach ($topics as $newTopicsKey => $newTopicsValue) {
                $newTopics[$newTopicsValue['test_paper_content_id']] = $newTopicsValue;
                foreach ($value['answer'] as $answerKey => $answerVal) {
                    if ($answerKey == $newTopicsValue['test_paper_content_id']) {
                        $answer[$newTopicsValue['id']] = $answerVal;
                    }
                }
            }
            // 查找每个考生的考题的详细信息
            $testPaperContentIds = array_column($topics, 'test_paper_content_id');
            $testPaperContents = TestPaperContentModel::instance()->getList(['all'=> true, 'id'=>$testPaperContentIds])->toArray();
            foreach ($testPaperContents as $contentKey => $contentVal) {
                $newTopics[$contentVal['id']]['content_info'] = $contentVal;
            }
            $upData = [];
            $questionBankConfig = json_decode($examTopicInfo['question_bank_config'], true);
            // 自动阅卷
            $isMustManual = false;
            foreach ($newTopics as $newKey => $newValue) {
                foreach ($answer as $answerKey => $answerVal) {
                    $saveAnswer = $answerVal;
                    if (is_array($answerVal)) {
                        $saveAnswer = join(',', $answerVal);
                    }
                    if ($answerKey === $newValue['id'] && !in_array($newValue['content_info']['type'], [3])) {
                        $isMustManual = true;
                        $upData[$newValue['id']] = [
                            'answer' => $saveAnswer,
                            'id' => $newValue['id']
                        ];
                        if ((string)$saveAnswer === (string)$newValue['content_info']['right_key']) {
                            $upData[$newValue['id']]['score'] = $questionBankConfig[$newValue['content_info']['type']]['score'];
                        } else {
                            $upData[$newValue['id']]['score']  = 0;
                        }
                        $upData[$newValue['id']]['is_deal']  = 1;
                    }
                    if ($answerKey === $newValue['id'] && !in_array($newValue['content_info']['type'], [0,1,2])) {
                        $upData[$newValue['id']] = [
                            'answer' => $saveAnswer,
                            'id' => $newValue['id']
                        ];
                    }
                }
            }
            $totalScore = 0;
            if (!$isMustManual) {
                foreach ($upData as $key => $val) {
                    $totalScore += $val['score'];
                }
            }
            // 更新考题信息
            EveryStudentTopicModel::instance()->upAll($upData);
            // 更新考试学生中间表信息
            StudentExamTopicModel::instance()->up([
                'exam_topic_id'=> $value['exam_topic_id'],
                'student_id' => $value['student_id']
            ],['status'=>2,'total_score'=>$totalScore]);
            var_dump('success');
        }
    }
}