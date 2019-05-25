<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/11 下午10:00
 * Description:
 */
namespace app\index\service;

use app\common\base\BaseController;
use app\common\base\BaseService;
use app\common\config\SelfConfig;
use app\common\model\EveryStudentTopicModel;
use app\common\model\ExamPaperModel;
use app\common\model\ExamTopicModel;
use app\common\model\StudentExamTopicModel;
use app\common\model\StudentsModel;
use app\common\model\TestPaperContentModel;
use app\common\tool\EncryptTool;
use app\common\tool\RabbitMQTool;
use app\common\tool\TimeTool;
use think\Db;

class ExamService extends BaseService {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/11 下午10:01
     * @return ExamService
     * Description: 返回当前对象
     */
    public static function instance() {
        return new ExamService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/11 下午10:09
     * @param $data
     * @return array | bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 考试验证页面
     */
    public function exam($data, &$msg) {
        // 解密数据
        $studentId = EncryptTool::decry($data['student_id'],SelfConfig::getConfig('Exam.sign_up_key'));
        if (!is_string($studentId) && strlen($studentId) <=0) {
            $msg = '解析考生id失败';
            return false;
        }
        $examTopicId = EncryptTool::decry($data['exam_topic_id'],SelfConfig::getConfig('Exam.sign_up_key'));
        if (!is_string($examTopicId) && strlen($examTopicId) <=0) {
            $msg = '解析考试专题id失败';
            return false;
        }
        // 获取考生信息
        $studentInfo = StudentsModel::instance()->getList([
            'id' => $studentId
        ])->toArray()['data'];
        if (empty($studentInfo)) {
            $msg = '获取考生信息失败';
            return false;
        }
        // 获取考试信息
        $examTopicInfo = ExamTopicModel::instance()->getList([
            'id' => $examTopicId
        ])->toArray()['data'];
        if (empty($examTopicInfo)) {
            $msg = '获取考试信息失败';
            return false;
        }
        $examTopicInfo[0]['test_start_time_new'] = str_replace('-0','-',date('Y-m-d-H-i-s', strtotime($examTopicInfo[0]['test_start_time'])));
        $examTopicInfo = $examTopicInfo[0];
        // 判断时间
        $testStartTime = strtotime($examTopicInfo['test_start_time']);
        $testStartLength = 60*$examTopicInfo['test_time_length'];
        $nowTime = time();
        if ($nowTime > $testStartTime+$testStartLength) {
            $msg = '考试已经结束';
            return false;
        }
        $isStart = 0;
        if ($testStartTime<=$nowTime && ($testStartTime+$testStartLength) >= $nowTime) {
            $isStart = 1;
        }
        return [
            'student_info' => $studentInfo[0],
            'exam_topic_info' => $examTopicInfo,
            'is_start' => $isStart
        ];
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/14 下午6:00
     * @param $data
     * Description:
     * @param $msg
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function testView($data, &$msg) {
        $res = Db::transaction(function () use ($data, &$msg) {
            $returnData = ['test_paper_info'=>array()];
            // 获取学生信息
            $studentInfo = StudentsModel::instance()->getList([
                'id' => $data['student_id']
            ])->toArray()['data'];
            if (empty($studentInfo)) {
                $msg = '获取考生信息失败';
                return false;
            }
            // 获取专题信息
            $studentInfo = $studentInfo[0];
            $examTopicInfo = ExamTopicModel::instance()->getList([
                'id' => $data['exam_topic_id']
            ])->toArray()['data'];
            if (empty($examTopicInfo)) {
                $msg = '获取考试信息失败';
                return false;
            }
            $examTopicInfo = $examTopicInfo[0];
            // 获取学生-专题中间表信息
            $studentExamTopicInfo = StudentExamTopicModel::instance()->getList([
                'student_id' => $data['student_id'],
                'exam_topic_id' => $data['exam_topic_id']
            ])->toArray()['data'];
            if (empty($studentExamTopicInfo)) {
                $msg = '获取中间表信息失败';
                return false;
            }
            $questionBankConfig = json_decode($examTopicInfo['question_bank_config'], true);
            $studentExamTopicInfo = $studentExamTopicInfo[0];
            if ($examTopicInfo['test_paper_type']) {
                // 是否分配了试卷
                if ($studentExamTopicInfo['is_distribution_test'] == 0) {
                    $addEveryStudetnData = array();
                    $allExamPaperData = array();
                    foreach ($questionBankConfig as $key => $value) {
                        if ($value['number'] == 0) {
                            continue;
                        }
                        $examPaperData = TestPaperContentModel::instance()->randSelect([
                            'question_bank_id' => $examTopicInfo['question_bank_id'],
                            'type' => $key,
                            'num' => $value['number']
                        ]);
                        if (empty($examPaperData)) {
                            continue;
                        }
                        $allExamPaperData[$key] = $examPaperData;
                        foreach ($examPaperData as $examPaperDataKey => $examPaperDataValue) {
                            $addEveryStudetnData[] = [
                                'test_paper_content_id' => $examPaperDataValue['id'],
                                'student_exam_topic_id' => $studentExamTopicInfo['id']
                            ];
                        }
                    }
                    if (empty($addEveryStudetnData)) {
                        $msg = '分配试卷有误';
                        return false;
                    }
                    // 将数据入每个考生库
                    $res = EveryStudentTopicModel::instance()->addTopic($addEveryStudetnData)->toArray();
                    if (empty($res)) {
                        $msg = '随机生成试题失败';
                        return false;
                    }
                    // 更新分配试卷状态
                    $res = StudentExamTopicModel::instance()->up(['id'=>$studentExamTopicInfo['id']],['is_distribution_test'=>1]);
                    if (!$res) {
                        $msg = '更新分配试卷状态失败';
                        return false;
                    }
                } else { // 已经分配试卷则直接查找试卷信息
                    $testPaperInfo = EveryStudentTopicModel::instance()->getList(['student_exam_topic_id'=>$studentExamTopicInfo['id']]);
                    $testPaperContentIds = [];
                    foreach ($testPaperInfo as $key => $value) {
                        $testPaperContentIds[] = $value['test_paper_content_id'];
                    }
                    $testPaperContentIds = array_unique($testPaperContentIds);
                    $allExamPaperData = TestPaperContentModel::instance()->getList(['id'=>$testPaperContentIds,'all'=>1])->toArray();
                }
            } else {
                // 固定试卷
                $testPaperInfo = EveryStudentTopicModel::instance()->getList([
                    'exam_topic_id'=>$data['exam_topic_id'],
                    'student_exam_topic_id' => ['<>',0]
                ])->toArray();
                $testPaperContentIds = [];
                foreach ($testPaperInfo as $key => $value) {
                    $testPaperContentIds[] = $value['test_paper_content_id'];
                }
                $testPaperContentIds = array_unique($testPaperContentIds);
                $allExamPaperData = TestPaperContentModel::instance()->getList(['id'=>$testPaperContentIds,'all'=>1])->toArray();
            }
            // 获取配置
            $examConfig = SelfConfig::getConfig('Exam.exam_type_base_conf');
            $bigNum = 0;
            foreach ($allExamPaperData as $key => $value) {
                if ($examTopicInfo['test_paper_type'] === 0) {
//                    $value = $value[0];
                }
                $value['option'] = json_decode($value['option'], true);
                if (!$value['option']) {
                    $value['option'] = array();
                }
                $value['empty_num'] = substr_count($value['title'],'_')+1;
//                if (isset($returnData['test_paper_info'][$value['type']])) {
                    $bigNum = count($returnData['test_paper_info']);
                    $returnData['test_paper_info'][$value['type']]['num'] = count($returnData['test_paper_info']);
                    $returnData['test_paper_info'][$value['type']]['big_title'] = $examConfig[$value['type']];
                    $returnData['test_paper_info'][$value['type']]['big_title']['num'] = count($returnData['test_paper_info']);
//                }
                $value['score'] = $questionBankConfig[$value['type']]['score'];
                $returnData['test_paper_info'][$value['type']]['data'][] = $value;
            }
            $examTopicInfo['test_start_time_new'] = str_replace('-0','-',date('Y-m-d-H-i-s', strtotime($examTopicInfo['test_start_time'])+$examTopicInfo['test_time_length']*60));
            $returnData['student_info'] = $studentInfo;
            $returnData['exam_topic_info'] = $examTopicInfo;
            $returnData['student_exam_topic_info'] = $studentExamTopicInfo;
            $returnData['big_num'] = $bigNum;
            return $returnData;
        });
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/18 下午5:00
     * @param $data
     * @param $msg
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     * @throws \Exception
     */
    public function testApply($data, &$msg) {
        // 投入mcq
        $res = RabbitMQTool::instance('apply_paper')->wMq(json_encode($data));
        $msg = '提交试卷成功';
        return $res;
    }

}