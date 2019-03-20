<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/3 下午6:31
 * Description:
 */
namespace app\common\tool;

class ShellTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/3 下午6:32
     * Description:
     */
    public static function synPhp($apiUrl,$params = []) {
        $php = 'php ';
        $apiUrl = dirname(dirname(dirname(dirname(__FILE__)))) . '/public/index.php ' . $apiUrl . ' ';
        $params = implode(' ', $params);
        $res = fclose(popen($php . $apiUrl . $params . " >/dev/null 2>&1 &", 'r')); // >/dev/null /dev/null 被称为位桶(bit bucket)或者黑洞(black hole)。空设备通常被用于丢弃不需要的输出流，或作为用于输入流的空文件。
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/20 下午12:11
     * @param $startFile
     * Description: 杀死进程
     */
    public static function kill($startFile) {
        exec("ps aux | grep $startFile | grep -v grep | awk '{print $2}'", $info);
        if (count($info) <= 1) {
            echo "not run\n";
        } else {
            echo "[$startFile] stop success";
            exec("ps aux | grep $startFile | grep -v grep | awk '{print $2}' |xargs kill -SIGINT", $info);
        }
    }


}