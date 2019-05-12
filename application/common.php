<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 模块名称
define('FUNC', 1001); // 系统功能
define('ROLE', 1002); // 角色
define('ADMIN', 1003); // 管理员
define('STUDENT_EXAMTOPIC', 1004); // 学生-考试专题
define('EVERY_STUDENT_TOPIC', 1005); // 每个学生的考题
define('EXAM_PAPER', 1006); // 题库
define('EXAM_TOPIC', 1007); // 考试专题
define('COUNT', 1008); // 统计
define('LOGIN', 1009); // 登录
define('PAPER_INSPECTION', 1010); // 手动阅卷
define('MODEL_LOG', 1011); // 模块日志

// 日志类型
define('ERROR', 101); // 错误日志
define('DEBUG', 102); // 调试日志
define('BUSINESS', 103); // 流程
// 应用公共文件

function console ($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}