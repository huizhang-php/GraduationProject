<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  题库service层
 */

namespace app\admin\service;
use app\common\base\BaseService;
use app\common\base\ServiceInter;
use app\common\config\SelfConfig;
use app\common\model\ExamPaperModel;
use app\common\model\TestPaperContentModel;
use app\common\tool\ExcelTool;
use app\common\tool\ShellTool;
use app\common\tool\TimeTool;
class ExamPaperService extends BaseService implements ServiceInter
{
    /**
     * 题库
     *
     * @var string
     * CreateTime: 2019/4/29 下午2:22
     */
    protected $modelName = 'exam_paper';

    /**
     * 返回当前对象
     *
     * @return ExamPaperService
     * CreateTime: 2019/4/29 下午2:22
     */
    public static function instance()
    {
        // TODO: Implement instance() method.
        return new ExamPaperService();
    }

    /**
     * 查找
     *
     * @param array $params
     * @return array|bool|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/29 下午2:23
     */
    public function getList($params=[])
    {
        // TODO: Implement getList() method.
        return ExamPaperModel::instance()->getList($params);
    }

    /**
     * 添加试卷
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:23
     */
    public function add($params=[], &$result)
    {
        // TODO: Implement add() method.
        // 查看专题名称是否重复
        $res = ExamPaperModel::instance()->getList(['name'=>$params['name']]);
        if (!empty($res->toArray()['data'])) {
            $result = '试卷名称重复';
            $this->wEsLog($result, $params);
            return false;
        }
        $data = [
            'name' => $params['name'],
            'introduction' => $params['introduction'],
            'staff' => session('admin_name'),
            'end_staff' => session('admin_name'),
        ];
        $res = ExamPaperModel::instance()->add($data);
        if ($res) {
            $result = '添加试卷成功';
            return true;
        }
        $result = '添加试卷失败';
        $this->wEsLog($result, $params);
        return false;
    }

    /**
     * 更新
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:23
     */
    public function up($params=[], &$result)
    {
        // TODO: Implement up() method.
        $condition['id'] = $params['id'];
        unset($params['id']);
        $params['end_staff'] = session('admin_name');
        $params['utime'] = TimeTool::getTime();
        $res = ExamPaperModel::instance()->up($condition, $params);
        if ($res) {
            $result = '修改成功';
            return true;
        } else {
            $result = '修改失败';
            $this->wEsLog($result, $params);
            return false;
        }
    }

    public function del($params=[], &$result)
    {
        // TODO: Implement del() method.
    }

    /**
     * 更新状态
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:24
     */
    public function up_status($params=[], &$result)
    {
        // TODO: Implement up_status() method.
        // 查看是否出题
        $condition['id'] = $params['id'];
        $res = ExamPaperModel::instance()->getList($condition);
        if (empty($res->toArray())) {
            $result = '状态修改失败';
            $this->wEsLog($result, $params);
            return false;
        }
        $data['status'] = $params['status'];
        $data['end_staff'] = session('admin_name');
        $data['utime'] = TimeTool::getTime();
        if (ExamPaperModel::instance()->up(['id'=>$params['id']], $data)) {
            $result = '状态修改成功';
            return true;
        }
        $result = '状态修改失败';
        $this->wEsLog($result, $params);
        return false;
    }

    /**
     * 保存
     *
     * @param array $params
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:24
     */
    public function saveTestPaper($params=[], &$result) {
        $bigTitles = $params['big_title'];
        $addBigTitleData = [];
        $examPaperId = $params['exam_paper_id'];
        unset($params['big_title'], $params['exam_paper_id']);
        // 统计有多少个大题
        $total = TestPaperContentModel::instance()->getList(['exam_paper_id'=>$examPaperId,
            'score' => 0]);
        $total = count($total);
        // 将大标题拼装入库
        foreach ($bigTitles as $key => $bigTitle) {
            $bigTitleInfo = explode(',', $bigTitle);
            $addBigTitleData[] = [
                'big_title' => $bigTitleInfo[0],
                'first_n'   => $key + $total + 1,
                'type'  => $bigTitleInfo[1],
                'exam_paper_id' => $examPaperId
            ];
        }
        if (!TestPaperContentModel::instance()->adds($addBigTitleData)) {
            $result = '批量插入大标题失败';
            $this->wEsLog($result, $params);
            return false;
        }
        $data = [];
        $newData = [];
        // 处理入库数据
        foreach ($params as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $topicInfo = explode(',', $key1);
                if (!isset($data[$topicInfo[0]][$topicInfo[1]])) {
                    $data[$topicInfo[0]][$topicInfo[1]] = [
                        'exam_paper_id' => $examPaperId,
                        'first_n' => $topicInfo[$topicInfo[1]] + $total
                    ];
                }
                foreach ($value1 as $key2 => $value2) {
                    switch ($key) {
                        case 'title':
                            $data[$topicInfo[0]][$topicInfo[1]]['title'] = $value2;
                            break;
                        case 'score':
                            $data[$topicInfo[0]][$topicInfo[1]]['score'] = $value2;
                            break;
                        case 'right_key':
                            $data[$topicInfo[0]][$topicInfo[1]]['right_key'] = $value2;
                            break;
                        case 'a':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['a'] = $value2;
                            break;
                        case 'b':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['b'] = $value2;
                            break;
                        case 'c':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['c'] = $value2;
                            break;
                        case 'd':
                            $data[$topicInfo[0]][$topicInfo[1]]['option']['d'] = $value2;
                            break;
                    }
                }
            }
        }
        // 将option转为json
        foreach ($data as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (isset($value1['option'])) {
                    $value[$key1]['option'] = json_encode($value1['option'], JSON_UNESCAPED_UNICODE);
                }
            }
            $newData = array_merge($newData, $value);
        }
        if (!TestPaperContentModel::instance()->adds($newData)) {
            $result = '批量插入小题失败';
            $this->wEsLog($result, $params);
            return false;
        }
        $result = '保存成功';
        return true;
    }

    /**
     * 查找信息
     *
     * @param $id
     * @return array
     * CreateTime: 2019/4/29 下午2:25
     */
    public function getTestPaperInfo($id) {
        $bigTitleList = [];
        $bigTitleInfo = TestPaperContentModel::instance()->getList(" exam_paper_id={$id} and score=0 ")->toArray();
        foreach ($bigTitleInfo as $key => $value) {
            $bigTitleList[$value['first_n']] = $value;
            $bigTitleList[$value['first_n']]['info'] = [];
        }
        $smallInfo = TestPaperContentModel::instance()->getList([
            'exam_paper_id'=>$id,
            'type' => -1
        ])->toArray();
        foreach ($smallInfo as $key => $value) {
            $value['option'] = json_decode($value['option'],true);
            $bigTitleList[$value['first_n']]['info'][] = $value;
        }
        return $bigTitleList;
    }

    /**
     * 异步导入文件
     *
     * @param $fileInfo
     * @param $id
     * @param $result
     * @return bool
     * CreateTime: 2019/4/29 下午2:25
     */
    public function uploadFile($fileInfo, $id, &$result)
    {
        // 投入异步php
        $res = ShellTool::synPhp(SelfConfig::getConfig('SynphpApi.syn_upload_question_bank'), [$fileInfo['complete_path'],$id]);
        if (!$res) {
            $result = '投入异步PHP失败';
            $this->wEsLog($result, [
                'fileinfo' => $fileInfo,
                'id' => $id
            ]);
            return false;
        }
        // 修改题库状态
        $res = ExamPaperModel::instance()->up(['id'=>$id], ['status'=>SelfConfig::getConfig('Exam.question_bank_status')['being_imported']]);
        if ($res) {
            $result = '正在导入请耐心等待';
            return true;
        }
        $result = '修改考题状态失败';
        $this->wEsLog($result, [
            'fileinfo' => $fileInfo,
            'id' => $id
        ]);
        return false;
    }

    /**
     * 获取某题库列表
     *
     * @param $id
     * @return array|bool|mixed|\PDOStatement|string|\think\Collection|\think\Paginator
     * CreateTime: 2019/4/29 下午2:26
     */
    public function getQuestionBankList($id) {
        $res = TestPaperContentModel::instance()->getList(['exam_paper_id'=>$id]);
        return $res;
    }
}