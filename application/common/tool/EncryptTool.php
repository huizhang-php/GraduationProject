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
     * CreateTime: 2019/3/6 下午11:08
     * @param $data
     * @param $key
     * @return bool|mixed|string
     * Description: 解密
     */
    public static function decry($data, $key) {
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/6 下午11:08
     * @param $data
     * @param $key
     * @return bool|mixed|string
     * Description: 加密
     */
    public static function encry($data, $key) {
        $key	=	md5($key);
        $x		=	0;
        $char = '';
        $str = '';
        $len	=	strlen($data);
        $l		=	strlen($key);
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
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

    /**
     * User: yuzhao
     * CreateTime: 2019/3/9 下午4:45
     * @return string
     * Description: 生成考生考号
     */
    public static function getStdNumber() {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}