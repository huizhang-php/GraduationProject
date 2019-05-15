<?php
/**
 * @CreateTime:   2019/5/13 下午4:55
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) Hebei normal university all rights reserved
 * @Description:  成绩查询control层
 */
namespace app\index\controller;


use app\common\base\BaseIndexController;
use app\index\service\ResultsQueryService;

class ResultsQueryController  extends BaseIndexController {

    /**
     * 成绩查询页面
     *
     * @return \think\response\View
     * CreateTime: 2019/5/13 下午5:09
     */
    public function results_query_view() {
        return view();
    }

    public function search() {
        $res = ResultsQueryService::instance()->search($this->params);
        $res = [
            'code' => 0,
            'count' => 10,
            'data' => $res,
        ];
        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }

}