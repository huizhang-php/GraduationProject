<?php
/**
 * @CreateTime:   2019/4/27 下午10:05
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  日志配置
 */
return [
    // 模块相关
    'modules' => [
        'func' => FUNC,
        'role' => ROLE,
        'admin' => ADMIN,
        'student_examtopic' => STUDENT_EXAMTOPIC,
        'every_student_topic' => EVERY_STUDENT_TOPIC,
        'exam_paper' => EXAM_PAPER,
        'exam_topic' => EXAM_TOPIC,
        'count' => COUNT,
        'login' => LOGIN,
        'paper_inspection' => PAPER_INSPECTION,
        'model_log' => MODEL_LOG,
    ],
    // 对应的模块名称
    'model_name' => [
        FUNC => '系统功能',
        ROLE => '角色',
        ADMIN => '管理员',
        STUDENT_EXAMTOPIC => '学生-考试专题',
        EVERY_STUDENT_TOPIC => '每个学生的考题',
        EXAM_PAPER => '题库',
        EXAM_TOPIC => '考试专题',
        COUNT => '统计',
        LOGIN => '登录',
        PAPER_INSPECTION => '手动阅卷',
        MODEL_LOG => '模块日志'
    ],
    // 日志类型
    'log_type' => [
        ERROR => '错误日志',
        DEBUG => 'Debug日志',
        BUSINESS => '过程日志'
    ]
];