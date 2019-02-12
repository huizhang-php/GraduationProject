<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/7 下午10:46
 * Description: 试题相关配置
 */

return [
    // 试题类型基础配置
    'exam_type_base_conf' => [
        0 => [
            'name'  => '单选',
            'score' => 3,
            'title' => '单选题。',
            'number'    => 15,
            'is_checked' => 1
        ],
        1 => [
            'name'  => '填空题',
            'score' => 2,
            'title' => '填空题。',
            'number'    => 20,
            'is_checked' => 0
        ],
        2 => [
            'name'  => '判断题',
            'score' => 1,
            'title' => '判断题。',
            'number'    => 10,
            'is_checked' => 0
        ],
        3 => [
            'name'  => '问答题',
            'score' => 10,
            'title' => '问答题。',
            'number'    => 3,
            'is_checked' => 0
        ],
    ]
];