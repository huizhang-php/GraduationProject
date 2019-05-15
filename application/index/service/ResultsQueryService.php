<?php
namespace app\index\service;
use app\common\model\StudentExamTopicModel;
use app\common\model\StudentsModel;

/**
 * @CreateTime:   2019/5/13 下午4:55
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  成绩查询Service层
 */

class ResultsQueryService {

    /**
     * 返回当前实例
     *
     * @return ResultsQueryService
     * CreateTime: 2019/5/13 下午5:13
     */
    public static function instance() {
        return new ResultsQueryService();
    }

    /**
     * 查询成绩
     *
     * @param $data
     * CreateTime: 2019/5/13 下午5:11
     */
    public function search($data){
        $condition = [
            'phone' => $data['phone']
        ];
        // 查询学生信息
        $student = StudentsModel::instance()->findStudent($condition);
        if (empty($student)) {
            return false;
        }
        $condition = [
            'student_id' => $student['id']
        ];
        // 查询考试成绩
        $res = StudentExamTopicModel::instance()->getList($condition);
        return $res;
    }
}
