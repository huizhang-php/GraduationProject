<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/6 下午11:47
 * Description:
 */

namespace app\index\service;

use app\common\config\SelfConfig;
use app\common\model\ExamTopicModel;
use app\common\model\StudentExamTopicModel;
use app\common\model\StudentsModel;
use app\common\tool\EncryptTool;
use app\common\tool\MailTool;
use app\common\tool\RedisTool;
use app\common\tool\SmsTool;
use think\Db;

class SignUpService{

    public static function instance() {
        return new SignUpService();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:52
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Description:
     */
    public function signUpView($id) {
        // 解密id
        $key = SelfConfig::getConfig('Exam.sign_up_key');
        $id = EncryptTool::decry($id, $key);
        // 获取考试专题信息
        $examTopicInfo = ExamTopicModel::instance()->getList(['id'=>$id]);
        $examTopicInfo = $examTopicInfo->toArray()['data'];
        if (empty($examTopicInfo)) {
            die('无效访问');
        }
        return $examTopicInfo[0];
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午3:59
     * @param $data
     * @param $result
     * Description:
     */
    public function sendSms($data, &$result) {
        // 查询手机号是否已经被注册
//        $examTopicId = EncryptTool::decry($data['exam_topic_id'],SelfConfig::getConfig('Exam.sign_up_key'));
//        $res = StudentsModel::instance()->getList([
//            'phone' => $data['phone']
//        ])->toArray();
//        if ($data['type'] == 1) {
//            if (!empty($res)) {
//                $result = '手机号已经被注册';
//                return false;
//            }
//        } else if ($data['type'] == 2) {
//            if (empty($res)) {
//                $result = '此手机号还没有被注册';
//                return false;
//            }
//        }
//        // 查看是否已经报名
//        $res = StudentsModel::instance()->getList([
//            'exam_topic_id' => $examTopicId,
//            'student_id' => $res[0]['id']
//        ])->toArray();
//        if (!empty($res)) {
//            $result = '您已经报名';
//            return false;
//        }
//        $config = SelfConfig::getConfig('ExtendApi.sms');
//        // 发送信息
//        $res = SmsTool::sendSms(4688,$data['phone'], $config['tpls'][4688]['content']);
//        if ($res===false || $res['error_code'] != 0) {
//            $result = '发送短信失败';
//            return false;
//        }
//        // 存入redis 5分钟
//        $res = RedisTool::instance()->setStr($data['phone'], $config['tpls'][4688]['code'], 300);
//        if (!$res) {
//            $result = '写入缓存失败';
//            return false;
//        }
        $result = '发送短信成功';
        return true;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/8 上午1:08
     * @param $data
     * @param $result
     * Description: 报名
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function signUp($data, &$result) {
        $res = Db::transaction(function () use ($data, &$result) {
            // 解析考试专题id
            $examTopicId = EncryptTool::decry($data['exam_topic_id'],SelfConfig::getConfig('Exam.sign_up_key'));
            // 查看手机号或者邮箱是否已经注册，并返回相应信息
            $res = StudentsModel::instance()->getList([
                'email' => ['or', $data['email']],
                'phone' => ['or', $data['phone']]
            ]);
            $res = $res->toArray();
            if (!empty($res)) {
                if ($res[0]['email'] == $data['email']) {
                    $result = '邮箱已经被注册';
                }
                if ($res[0]['phone'] == $data['phone']) {
                    $result = '手机号已经被注册';
                }
                return false;
            }
            // 查看是否已经报名
            $res = StudentExamTopicModel::instance()->getList([
                'exam_topic_id' => $examTopicId,
                'student_id' => $res[0]['id']
            ])->toArray();
            if (!empty($res)) {
                $result = '您已经报名';
                return false;
            }
            // 验证手机号+验证码是否跟缓存中一致
            $phoneInfo = RedisTool::instance()->getStr($data['phone']);
            if (!is_string($phoneInfo) || $phoneInfo != $data['code']) {
                $result = '手机验证码错误';
                return false;
            }
            unset($data['code']);
            $data['id'] = EncryptTool::getStdNumber();
            // 入考生库
            $res = StudentsModel::instance()->addStudent($data);
            if (!$res) {
                $result = '入考生库失败';
                return false;
            }
            // 入考生-考试专题中间表
            $res = StudentExamTopicModel::instance()->addStudentExamTopic([
                'exam_topic_id' => $examTopicId,
                'student_id' => $data['id']
            ]);
            if (!$res) {
                $result = '入中间表失败';
                return false;
            }
            $examTopicInfo = ExamTopicModel::instance()->getList(['id'=>$examTopicId]);
            $examTopicInfo = $examTopicInfo->toArray()['data'];
            if (!isset($examTopicInfo[0])) {
                $result = '查询考试专题失败';
                return false;
            }
            $title = $examTopicInfo['name'].'是否确认报名?';
            $studentEncryNumber = EncryptTool::encry($data['id'], SelfConfig::getConfig('Exam.sign_up_key'));
            $body = "http://www.tuzisir.com:8082/index.php/index/sign_up/confirm_sign_up?exam_topic_id={$data['exam_topic_id']}&student_id=$studentEncryNumber";
            // 发送确认邮件
            $res = MailTool::instance()->sendMail($title,$body,$data['email'],$data['name']);
            if ($res) {
                // 返回结果
                $result = '注册报名成功';
                return true;
            }
            $result = '发送邮件失败';
            return false;
        });
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午9:34
     * @param $data
     * @param $result
     * Description:
     * @return mixed
     */
    public function apply($data, &$result) {
        $res = Db::transaction(function () use ($data, &$result) {
            $examTopicId = EncryptTool::decry($data['exam_topic_id'],SelfConfig::getConfig('Exam.sign_up_key'));
            // 查看手机号或者邮箱是否已经注册，并返回相应信息
            $studentInfo = StudentsModel::instance()->getList([
                'phone' => $data['phone']
            ])->toArray()['data'];
            if (empty($studentInfo)) {
                $result = '此手机号还没有被注册';
                return false;
            }
            // 验证手机号+验证码是否跟缓存中一致
            $phoneInfo = RedisTool::instance()->getStr($data['phone']);
            if ($phoneInfo===false || !is_string($phoneInfo) || $phoneInfo != $data['code']) {
                $result = '手机验证码错误';
//                return false;
            }
            // 查看是否已经报名
            $res = StudentExamTopicModel::instance()->getList([
                'exam_topic_id' => $examTopicId,
                'student_id' => $studentInfo[0]['id']
            ])->toArray()['data'];
            if (!empty($res)) {
                $result = '您已经报名';
                return false;
            }
            // 入考生-考试专题中间表
            $res = StudentExamTopicModel::instance()->addStudentExamTopic([
                'exam_topic_id' => $examTopicId,
                'student_id' => $studentInfo[0]['id']
            ]);
            if (!$res) {
                $result = '入中间表失败';
                return false;
            }
            $examTopicInfo = ExamTopicModel::instance()->getList(['id'=>$examTopicId]);
            $examTopicInfo = $examTopicInfo->toArray()['data'];
            if (!isset($examTopicInfo[0])) {
                $result = '查询考试专题失败';
                return false;
            }
            $title = $examTopicInfo[0]['name'].'是否确认报名?';
            $studentEncryNumber = EncryptTool::encry($studentInfo[0]['id'], SelfConfig::getConfig('Exam.sign_up_key'));
            $body = "http://www.tuzisir.com:8082/index.php/index/sign_up/confirm_sign_up?exam_topic_id={$data['exam_topic_id']}&student_id=$studentEncryNumber";
            // 发送确认邮件
            $res = MailTool::instance()->sendMail($title,$body,$studentInfo[0]['email'],$studentInfo[0]['name']);
            $result = '报名成功';
            return true;
        });
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午11:59
     * @param $data
     * @param $result
     * @return bool
     * Description: 确认报名
     */
    public function confirmSignUp($data, &$result) {
        // 解析考试专题id
        $examTopicId = EncryptTool::decry($data['exam_topic_id'], SelfConfig::getConfig('Exam.sign_up_key'));
        if (!(is_string($examTopicId) && strlen($examTopicId) > 5) ) {
            $result = '专题编号错误';
            return false;
        }
        // 解析考生编号
        $studentId = EncryptTool::decry($data['student_id'], SelfConfig::getConfig('Exam.sign_up_key'));
        if (!(is_string($studentId) && strlen($studentId) > 5) ) {
            $result = '考生编号错误';
            return false;
        }
        $res = StudentExamTopicModel::instance()->getList([
            'exam_topic_id' => $examTopicId,
            'student_id' => $studentId,
            'status'=>1
        ])->toArray()['data'];
        if (!empty($res)) {
            $result = '您已经报名';
            return false;
        }
        // 更改报名状态
        $res = StudentExamTopicModel::instance()->up([
            'exam_topic_id' => $examTopicId,
            'student_id' => $studentId
        ],['status'=>1]);
        if ($res) {
            $result = '报名成功';
            return true;
        }
        $result = '报名失败';
        return false;
    }
}