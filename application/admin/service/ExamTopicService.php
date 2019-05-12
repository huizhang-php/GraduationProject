<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/11 下午5:34
 * Description:
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
use app\common\tool\EncryptTool;
use think\Db;

class ExamTopicService extends BaseService implements ServiceInter {

    /**
     * 模块名称
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:28
     */
    protected $modelName = 'exam_topic';

    /**
     * 返回当前实例
     *
     * @return ExamTopicService
     * CreateTime: 2019/4/29 下午2:27
     */
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new ExamTopicService();
    }

    /**
     * 添加考试专题
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:28
     */
    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        // 查看专题名称是否重复
        $res = ExamTopicModel::instance()->getList(['name'=>$params['name']]);
        if (!empty($res->toArray()['data'])) {
            $result = '专题名称重复';
            $this->wEsLog($result, $params);
            return false;
        }
        $questionBank = str_replace("'",'',json_encode($params['question_bank'], JSON_UNESCAPED_UNICODE));
        $questionBank = json_decode($questionBank,true);
        $uuid = EncryptTool::getUniqid();
        $data = [
            'id' => $uuid,
            'name' => $params['name'],
            'introduction' => $params['introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name'),
            'question_bank_config' => str_replace("'",'',json_encode($params['question_bank'], JSON_UNESCAPED_UNICODE)),
            'test_paper_type' => $params['test_paper_type'],
            'test_start_time' => $params['test_start_time'],
            'test_time_length' => $params['test_time_length'],
            'question_bank_id' => $params['question_bank_id']
        ];
        $res = ExamTopicModel::instance()->save($data);
        if (!$res) {
            $result = '添加专题失败';
            $this->wEsLog($result, $data);
            return false;
        }
        // 统一组卷
        if ($params['test_paper_type'] == 0) {
            // 随机将题抽出
            foreach ($questionBank as $key => $value) {
                if ($value['number'] == 0) {
                    continue;
                }
                $examPaperData = TestPaperContentModel::instance()->randSelect([
                    'question_bank_id' => $params['question_bank_id'],
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
                        'test_paper_type' => 0,
                        'exam_topic_id' => $uuid
                    ];
                }
            }
            if (empty($addEveryStudetnData)) {
                $result = '分配试卷有误';
                $this->wEsLog($result, $params);
                return false;
            }
            // 入库
            $res = EveryStudentTopicModel::instance()->addTopic($addEveryStudetnData);
            if (empty($res)) {
                $result = '生成试题失败';
                $this->wEsLog($result, $addEveryStudetnData);
                return false;
            }
        }
        $result = '添加专题成功';
        return true;
    }

    /**
     * 查找
     *
     * @param array $params
     * @return array|bool|mixed|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/29 下午2:29
     */
    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        $sign = SelfConfig::getConfig('Exam.sign_up_key');
        $res = ExamTopicModel::instance()->getList($params);
        $nowTime = time();
        foreach ($res as $key => $value) {
            $startTime = strtotime($value['test_start_time']);
            $endTime = strtotime($value['test_start_time'])+$value['test_time_length'];
            if ($nowTime<$startTime) {
                $status = '待考';
            } elseif ($nowTime>$endTime) {
                $status = '结束';
            } elseif ($nowTime>$startTime and $nowTime<$endTime) {
                $status = '正在考试';
            }
            $res[$key]['status'] = $status;
            $res[$key]['encrypt'] = EncryptTool::encry($value['id'], $sign);
        }
        return $res;
    }

    /**
     * 更新
     *
     * @param array $params
     * @param $result
     * @return mixed
     * CreateTime: 2019/4/29 下午2:30
     */
    public function up($params=[], &$result)
    {
        // TODO: Implement up() method.
        $res = Db::transaction(function () use ($params, &$result) {
            $condition['id'] = $params['id'];
            $res = ExamTopicModel::instance()->getList(['name'=>$params['name'],'id'=>['<>',$params['id']]]);
            $res = $res->toArray()['data'];
            if (!empty($res)) {
                $result = '专题名称重复';
                $this->wEsLog($result, $params);
                return false;
            }
            $questionBank = str_replace("'",'',json_encode($params['question_bank'], JSON_UNESCAPED_UNICODE));
            $questionBank = json_decode($questionBank,true);
            $data = [
                'name' => $params['name'],
                'introduction' => $params['introduction'],
                'end_staff' => session('admin_name'),
                'question_bank_config' => str_replace("'",'',json_encode($params['question_bank'], JSON_UNESCAPED_UNICODE)),
                'test_paper_type' => $params['test_paper_type'],
                'test_start_time' => $params['test_start_time'],
                'test_time_length' => $params['test_time_length'],
                'question_bank_id' => $params['question_bank_id'],
                'utime' => TimeTool::getTime()
            ];
            $res = ExamTopicModel::instance()->up($condition, $data);
            if (!$res) {
                $result = '修改失败';
                $this->wEsLog($result, [
                    'condition' => $condition,
                    'data' => $data
                ]);
                return false;
            }
            // 删除信息
            if ($params['test_paper_type'] == 0) {
                $res = EveryStudentTopicModel::instance()->del(['exam_topic_id'=>$params['id'],'student_exam_topic_id'=>0]);
                if (!$res) {
                    $result = '删除试题失败';
                    $this->wEsLog($result, $params);
                    return false;
                }
            }
            // 统一组卷
            if ($params['test_paper_type'] == 0) {
                // 随机将题抽出
                foreach ($questionBank as $key => $value) {
                    if ($value['number'] == 0) {
                        continue;
                    }
                    $examPaperData = TestPaperContentModel::instance()->randSelect([
                        'question_bank_id' => $params['question_bank_id'],
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
                            'test_paper_type' => 0,
                            'exam_topic_id' => $params['id']
                        ];
                    }
                }
                if (empty($addEveryStudetnData)) {
                    $result = '分配试卷有误';
                    $this->wEsLog($result, $params);
                    return false;
                }
                // 入库
                $res = EveryStudentTopicModel::instance()->addTopic($addEveryStudetnData);
                if (empty($res)) {
                    $result = '生成试题失败';
                    $this->wEsLog($result, $addEveryStudetnData);
                    return false;
                }
            }
            $result = '修改成功';
            return true;
        });
        return $res;
    }

    public function del($params=[], &$result)
    {
        // TODO: Implement del() method.
    }

    /**
     * 更新考试专题状态
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:31
     */
    public function up_status($params=[], &$result)
    {
        // TODO: Implement up_status() method.
        // 查看试卷是否准备就绪
        $condition['id'] = $params['id'];
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
        $data['utime'] = TimeTool::getTime();
        if (ExamTopicModel::instance()->up($condition, $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        $this->wEsLog($result, $params);
        return false;
    }

    /**
     * 获取考试专题学生信息
     *
     * @param $data
     * @return bool|\think\Paginator
     * CreateTime: 2019/4/29 下午2:31
     */
    public function examTopicStudents($data) {
        $res = StudentExamTopicModel::instance()->getList([
            'exam_topic_id' => $data['exam_topic_id']
        ]);
        return $res;
    }
}