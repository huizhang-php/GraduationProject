<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/3 下午10:46
 * Description: 题库异步
 */
namespace app\synphp\controller;
use app\common\base\SynPhpBase;
use app\common\config\SelfConfig;
use app\common\model\ExamPaperModel;
use app\common\model\TestPaperContentModel;
use app\common\tool\ExcelTool;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class UploadQuestionBankController extends SynPhpBase {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/3 下午11:17
     * Description: 异步加载题库
     */
    public function uploadQuestionBankDeal()
    {
        // 获取excel数据
        try {
            $res = ExcelTool::getExcelContent($this->params[0]);
        } catch (Exception $e) {
            // 记录日志
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // 记录日志
        }
        // 判断表头是否正确
        $saveData = [];
        if (count($res[0]) == 5) {
            unset($res[0]);
            foreach ($res as $key => $value) {
                if ($value[0] == NULL) {
                    break;
                }
                $option = explode(',',$value[1]);
                $optionJson = [];
                foreach ($option as $opKey => $opValue) {
                    $opValue = explode('=', $opValue);
                    if (count($opValue) != 2) {
                        continue;
                    }
                    $optionJson[$opValue[0]] = $opValue[1];
                }
                if (!empty($optionJson)) {
                    $optionJson = json_encode($optionJson, JSON_UNESCAPED_UNICODE);
                } else {
                    $optionJson = '';
                }
                $saveData[] = [
                    'title' => $value[0],
                    'option' => $optionJson,
                    'right_key' => $value[2],
                    'type' => $value[3],
                    'exam_paper_id' => $this->params[1]
                ];
            }
        }
        $res = TestPaperContentModel::instance()->adds($saveData);
        if ($res) {
            $res = ExamPaperModel::instance()->up(['id'=>$this->params[1]], [
                'status'=>SelfConfig::getConfig('Exam.question_bank_status')['normal']
            ]);
        } else {

        }
    }
}
