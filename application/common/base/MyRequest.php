<?php
/**
 * User: yuzhao
 * CreateTime: 2019/1/6 下午3:56

 * Description:
 */
namespace app\common\base;
use app\common\config\SelfConfig;
use think\facade\Request;
use think\File;

trait MyRequest {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/2 下午7:52
     * @var array
     * Description: 普通信息
     */
    public $params = array();

    /**
     * User: yuzhao
     * CreateTime: 2019/3/2 下午7:52
     * @var array
     * Description: 上传文件的信息
     */
    public $fileParams = array();

    /**
     * User: yuzhao
     * CreateTime: 2019/3/2 下午7:53
     * Description: 获取普通参数
     */
    public function getParams() {
        $this->params = Request::post();
        $this->params = array_merge($this->params, Request::get());
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/2 下午7:50
     * Description: 获取文件,支持多文件
     */
    public function getFile($filePath, $fileType=null) {
        if ($fileType != null) {
            $res = Request::file($fileType);
        } else {
            $res = Request::file();
        }
        $uploadFilePath = SelfConfig::getConfig('Project.file_path');
        $returnInfo = [];
        /** @var $value File*/
        foreach ($res as $value) {
            $info = $value->move( $uploadFilePath.$filePath);
            if ($info) {
                $returnInfo[] = [
                    'save_name' => $info->getSaveName(),
                    'ext' => $info->getExtension(),
                    'file_name' => $info->getFilename(),
                    'complete_path' => $uploadFilePath.$filePath.'/'.$info->getSaveName()
                ];
            } else {
                return false;
            }
        }
        return $returnInfo;
    }

}