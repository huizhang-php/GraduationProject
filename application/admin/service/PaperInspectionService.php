<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  手动判断service层
 */
namespace app\admin\service;

use app\common\base\BaseService;
use app\common\base\ServiceInter;
use app\common\config\SelfConfig;
use app\common\model\EveryStudentTopicModel;
use app\common\model\ExamTopicModel;
use app\common\model\StudentExamTopicModel;
use app\common\model\TestPaperContentModel;
use app\common\tool\TimeTool;

class PaperInspectionService extends BaseService implements ServiceInter {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:40
     */
    protected $modelName = 'paper_inspection';

    /**
     * 获取当前实例
     *
     * @return PaperInspectionService
     * CreateTime: 2019/4/29 下午2:40
     */
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new PaperInspectionService();
    }

    /**
     * 查找
     *
     * @param array $params
     * @return array|bool|mixed|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/29 下午2:41
     */
    public function getList($params = [])
    {
        // TODO: Implement getList() method.
        // 获取考试过后的信息
        $examTopicInfo = ExamTopicModel::instance()->getList([
            'status' => 2
        ]);
        return $examTopicInfo;
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

    /**
     * 获取考卷信息
     *
     * @param array $params
     * @return mixed
     * CreateTime: 2019/4/29 下午2:41
     */
    public function paperInspection($params=[]) {
        // 获取所有考生
        $studentInfo = StudentExamTopicModel::instance()->getList([
            'exam_topic_id' => $params['exam_topic_id']
        ]);
        $newStudentInfo['students'] = [];
        $newStudentInfo['count'] = $studentInfo;
        foreach ($studentInfo as $key => $value) {
            $valueArr = $value->toArray();
            $newStudentInfo['students'][$valueArr['id']] = $value;
        }
        // 获取考试专题信息
        $examTopicInfo = ExamTopicModel::instance()->getList([
            'id' => $params['exam_topic_id']
        ])->toArray()['data'][0];
        $testConfig = json_decode($examTopicInfo['question_bank_config'], true);
        // 获取考生试卷
        $studentExamTopicIds = array_column($studentInfo->toArray()['data'],'id');
        // 获取中间表信息
        $everyTopicInfo = EveryStudentTopicModel::instance()->getList([
            'student_exam_topic_id' => $studentExamTopicIds,
            'is_deal' => 0
        ])->toArray();
        if (empty($everyTopicInfo)) {
            $newStudentInfo['students'] = [];
            $this->wEsLog('获取中间表信息失败', [
                'student_exam_topic_id' => $studentExamTopicIds,
                'is_deal' => 0
            ]);
        }
        // 获取试卷原始信息
        $testPaperContentIds = array_column($everyTopicInfo, 'test_paper_content_id');
        $testPaperContents = TestPaperContentModel::instance()->getList(['all'=>true, 'id'=>$testPaperContentIds])->toArray();
        $newEveryTopicInfo = [];
        foreach ($everyTopicInfo as $key => $value) {
            foreach ($testPaperContents as $key1 => $value1) {
                if ($value1['id'] == $value['test_paper_content_id']) {
                    $value1['option'] = json_decode($value1['option'], true);
                    $value1['score'] = $testConfig[$value1['type']]['score'];
                    $value['content'] = $value1;
                }
            }
            $newEveryTopicInfo[$value['student_exam_topic_id']][] = $value;
        }
        foreach ($newEveryTopicInfo as $key => $value) {
            foreach ($newStudentInfo['students'] as $key1 => $value1) {
                if ($key === $key1) {
                    $newStudentInfo['students'][$key]['student_exam_topic_id'] = $value[0]['student_exam_topic_id'];
                    $newStudentInfo['students'][$key]['student_paper_info'] = $value;
                }
            }
        }
        foreach ($newStudentInfo['students'] as $key => $value) {
            if (!isset($value['student_paper_info'])) {
                unset($newStudentInfo['students'][$key]);
            }
        }
        return $newStudentInfo;
    }

    /**
     * 阅卷
     *
     * @param $data
     * @param $msg
     * @return bool
     * CreateTime: 2019/4/29 下午2:42
     */
    public function lookTestPaper($data, &$msg) {
        $upData = [];
        foreach ($data['score'] as $key => $value) {
            $upData[] = [
                'id' => $key,
                'score' => $value,
                'is_deal' => true,
                'utime' => TimeTool::getTime()
            ];
        }
        $res = EveryStudentTopicModel::instance()->upAll($upData);
        if (!$res) {
//            $msg = '提交失败';
//            $this->wEsLog($msg, $data);
//            return false;
        }
        $upScoreData = [];
        // 统计总分
        $res = EveryStudentTopicModel::instance()->countScore($data['student_exam_topic_id']);
        foreach ($res as $key => $val) {
            $upScoreData[] = [
                'id' => $val['student_exam_topic_id'],
                'total_score' => $val['total']
            ];
        }
        $res = StudentExamTopicModel::instance()->upAll($upScoreData);
        if (false === $res) {
            $msg = '提交失败';
            $this->wEsLog($msg, $data);
            return false;
        }
        $msg = '提交成功';
        return true;
    }
}