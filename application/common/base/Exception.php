<?php
/**
 * @CreateTime:   2019/4/27 下午10:42
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:
 */
namespace app\common\tool;

use Throwable;

class Exception implements \Throwable {

    /**
     * Gets the message
     * @link http://php.net/manual/en/throwable.getmessage.php
     * @return string
     * @since 7.0
     */
    public function getMessage()
    {
        // TODO: Implement getMessage() method.
    }

    /**
     * Gets the exception code
     * @link http://php.net/manual/en/throwable.getcode.php
     * @return int <p>
     * Returns the exception code as integer in
     * {@see Exception} but possibly as other type in
     * {@see Exception} descendants (for example as
     * string in {@see PDOException}).
     * </p>
     * @since 7.0
     */
    public function getCode()
    {
        // TODO: Implement getCode() method.
    }

    /**
     * Gets the file in which the exception occurred
     * @link http://php.net/manual/en/throwable.getfile.php
     * @return string Returns the name of the file from which the object was thrown.
     * @since 7.0
     */
    public function getFile()
    {
        // TODO: Implement getFile() method.
    }

    /**
     * Gets the line on which the object was instantiated
     * @link http://php.net/manual/en/throwable.getline.php
     * @return int Returns the line number where the thrown object was instantiated.
     * @since 7.0
     */
    public function getLine()
    {
        // TODO: Implement getLine() method.
    }

    /**
     * Gets the stack trace
     * @link http://php.net/manual/en/throwable.gettrace.php
     * @return array <p>
     * Returns the stack trace as an array in the same format as
     * {@see debug_backtrace()}.
     * </p>
     * @since 7.0
     */
    public function getTrace()
    {
        // TODO: Implement getTrace() method.
    }

    /**
     * Gets the stack trace as a string
     * @link http://php.net/manual/en/throwable.gettraceasstring.php
     * @return string Returns the stack trace as a string.
     * @since 7.0
     */
    public function getTraceAsString()
    {
        // TODO: Implement getTraceAsString() method.
    }

    /**
     * Returns the previous Throwable
     * @link http://php.net/manual/en/throwable.getprevious.php
     * @return Throwable Returns the previous {@see Throwable} if available, or <b>NULL</b> otherwise.
     * @since 7.0
     */
    public function getPrevious()
    {
        // TODO: Implement getPrevious() method.
    }

    /**
     * Gets a string representation of the thrown object
     * @link http://php.net/manual/en/throwable.tostring.php
     * @return string <p>Returns the string representation of the thrown object.</p>
     * @since 7.0
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
    }
}