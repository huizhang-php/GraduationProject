<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/11 下午10:00
 * Description:
 */
namespace app\index\service;

use app\common\base\BaseController;
use app\common\config\SelfConfig;
use app\common\model\ExamTopicModel;
use app\common\model\StudentsModel;
use app\common\tool\EncryptTool;

class ExamService extends BaseController{

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
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description: 考试验证页面
     */
    public function exam($data) {
        // 解密数据
        $studentId = EncryptTool::decry($data['student_id'],SelfConfig::getConfig('Exam.sign_up_key'));
        if (!is_string($studentId) && strlen($studentId) <=0) {
            die('解析考生id失败');
        }
        $examTopicId = EncryptTool::decry($data['exam_topic_id'],SelfConfig::getConfig('Exam.sign_up_key'));
        if (!is_string($examTopicId) && strlen($examTopicId) <=0) {
            die('解析考试专题id失败');
        }
        // 获取考生信息
        $studentInfo = StudentsModel::instance()->getList([
            'id' => $studentId
        ])->toArray()['data'];
        if (empty($studentInfo)) {
            die('获取考生信息失败');
        }
        // 获取考试信息
        $examTopicInfo = ExamTopicModel::instance()->getList([
            'id' => $examTopicId
        ])->toArray()['data'];
        if (empty($studentInfo)) {
            die('获取考试信息失败');
        }
        $examTopicInfo[0]['test_start_time_new'] = str_replace('-0','-',date('Y-m-d-h-i-s', strtotime($examTopicInfo[0]['test_start_time'])));
        return [
            'student_info' => $studentInfo[0],
            'exam_topic_info' => $examTopicInfo[0]
        ];
    }

}