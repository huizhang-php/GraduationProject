<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/8 上午11:37
 * Description:
 */
namespace app\common\tool;

use app\common\config\SelfConfig;

class SmsTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/8 上午11:37
     * Description: 发送短信
     * @param $tplId 模板id
     * @param $mobile 手机号
     * @param $content 模板内容
     * @return bool|mixed
     */
    public static function sendSms($tplId, $mobile, $content) {
        $sms = SelfConfig::getConfig('ExtendApi.sms');
        $content = urlencode($content);
        $curl = curl_init();
        $url = "{$sms['send']}?tpl_id={$tplId}&key={$sms['key']}&mobile={$mobile}&content={$content}";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return json_decode($response, true);
        }
    }
}