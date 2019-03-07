<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/6 下午11:47
 * Description:
 */

namespace app\index\service;


use app\admin\service\ExamTopicService;
use app\common\config\SelfConfig;
use app\common\model\ExamTopicModel;
use app\common\tool\EncryptTool;

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
     * CreateTime: 2019/3/8 上午1:08
     * @param $data
     * @param $result
     * Description: 报名
     */
    public function signUp($data, &$result) {
        
    }
}