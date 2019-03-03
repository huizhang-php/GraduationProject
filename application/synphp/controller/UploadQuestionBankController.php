<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/3 下午10:46
 * Description: 题库异步
 */
namespace app\synphp\controller;
use app\common\base\SynPhpBase;

class UploadQuestionBankController extends SynPhpBase {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/3 下午11:17
     * Description: 异步加载题库
     */
    public function uploadQuestionBankDeal() {
        // 获取excel数据
        ShellTool::synPhp(SelfConfig::getConfig('SynphpApi.syn_upload_question_bank'), [$fileInfo['complete_path']]);

    }
}
