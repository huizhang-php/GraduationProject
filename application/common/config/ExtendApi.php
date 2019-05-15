<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/8 下午2:31
 * Description:
 */
$code = rand(1000,9999);
return [
    // 短信
    'sms' => [
        'key' => '3d396b6789154aa3bf0efd9fbf305629',
        'send' => 'http://apis.haoservice.com/sms/sendv2',
        'tpls' => [
            4688=> [
                'code' => $code,
                'content'=>'【tuzisir】您的验证码是'.$code.'，如非本人操作，请忽略本短信。',
            ]
        ]
    ]
];