<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/19 下午10:19
 * Description:
 */
namespace app\admin\service;

use app\common\base\ServiceInter;
use app\common\model\EveryStudentTopicModel;
use app\common\model\ExamTopicModel;
use app\common\model\StudentExamTopicModel;

class PaperInspectionService implements ServiceInter {

    public static function instance()
    {
        // TODO: Implement instance() method.
        return new PaperInspectionService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/19 下午10:34
     * @param array $params
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
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
     * User: yuzhao
     * CreateTime: 2019/3/19 下午11:10
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
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
        // 获取考生试卷
        $studentExamTopicIds = array_column($studentInfo->toArray()['data'],'id');
        $everyTopicInfo = EveryStudentTopicModel::instance()->getList([
            'student_exam_topic_id' => $studentExamTopicIds
        ]);
        $newEveryTopicInfo = [];
        foreach ($everyTopicInfo as $key => $value) {
            $valueArr = $value->toArray();
            $newEveryTopicInfo[$valueArr['student_exam_topic_id']][] = $value;
        }
        foreach ($newEveryTopicInfo as $key => $value) {
            if (array_key_exists($valueArr['student_exam_topic_id'], $newStudentInfo['students'])) {
                $newStudentInfo['students'][$valueArr['student_exam_topic_id']]['student_paper_info'] = $value;
            }
        }
        return $newStudentInfo;
    }
}