<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/6 下午10:59
 * Description:
 */
namespace app\common\tool;
class EncryptTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:00
     * @param string $string 需要加密的字符串
     * @param bool $operation 判断是否需要加解密
     * @param string $key 秘钥
     * @param int $expiry
     * @return bool|mixed|string
     * Description: 双向加解密* Description: 双向加解密
     */
    private static function baseEncry($string, $key = '', $operation = false, $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;
        // 密匙
        $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation ? substr($string, 0, $ckey_length):
            substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
        //解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation ? base64_decode(substr($string, $ckey_length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
                substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:08
     * @param $str
     * @param $key
     * @return bool|mixed|string
     * Description: 解密
     */
    public static function decry($str, $key, $expiry=0) {
        $res = self::baseEncry($str, $key, true, $expiry);
        if (is_string($res) && strlen($res) > 0) {
            return $res;
        }
        return false;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:08
     * @param $str
     * @param $key
     * @return bool|mixed|string
     * Description: 加密
     */
    public static function encry($str, $key, $expiry=0) {
        $res = self::baseEncry($str, $key, false, $expiry);
        if (is_string($res) && strlen($res) > 0) {
            return $res;
        }
        return false;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:10
     * @return string
     * Description: 获取唯一编号
     */
    public static function getUniqid() {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        return $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }
}