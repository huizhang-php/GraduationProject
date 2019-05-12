<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/20 下午12:13
 * Description:
 */
namespace app\common\tool;

class ProcessTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/19 下午4:18
     * Description: 守护进程模式启动
     */
    public static function daemonStart() {
        // 守护进程需要pcntl扩展支持
        if (!function_exists('pcntl_fork'))
        {
            exit('Daemonize needs pcntl, the pcntl extension was not found');
        }
        umask( 0 );
        $pid = pcntl_fork();
        if( $pid < 0 ){
            exit('fork error.');
        } else if( $pid > 0 ) {
            exit();
        }
        if( !posix_setsid() ){
            exit('setsid error.');
        }
        $pid = pcntl_fork();
        if( $pid  < 0 ){
            exit('fork error');
        } else if( $pid > 0 ) {
            // 主进程退出
            exit;
        }
        // 子进程继续，实现daemon化
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/20 下午1:05
     * @param $file
     * Description: 不能重复启动
     */
    public static function noRepeatStart($file) {
        $fhanlde = fopen($file,'r');
        $r = flock($fhanlde,LOCK_EX|LOCK_NB);
        if(!$r){
            die('不能重复启动');
        }
    }
}