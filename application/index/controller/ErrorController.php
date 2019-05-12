<?php
/**
 * @CreateTime:   2019/4/5 下午11:53
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  错误处理
 */

namespace app\index\controller;

use app\common\base\BaseIndexController;

class ErrorController extends BaseIndexController {

    public function error_view() {
        $msg = $this->params['msg'];
        $this->assign([
            'msg' => $msg
        ]);
        return $this->fetch('index@error/error');
    }
}