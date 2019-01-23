<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午3:59

 * Description:
 */
namespace app\common\base;
trait MyResponse {

    public function returnAjax($code=200, $msg='', $data=[]) {
        die(json_encode([
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ],JSON_UNESCAPED_UNICODE));
    }
}