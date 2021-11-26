<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------

/**
 * ajax请求成功返回的数据结构
 * @param string $msg
 * @param array $data
 * @param int $code
 * @return \think\response\Json
 */
function ajaxSuccess($msg = '', $data = [], $code = 0){
    return json([
        'data'  => $data,
        'msg'   => $msg,
        'code'  => $code
    ]);
}

/**
 * ajax请求失败返回的数据结构
 * @param string $msg
 * @param int $code
 * @param array $data
 * @return \think\response\Json
 */
function ajaxFail($msg = '', $code = 1000, $data = []){
    return json([
        'data'  => $data,
        'msg'   => $msg,
        'code'  => $code
    ]);
}

/**
 * 获取路由信息
 * @param $request
 * @return array
 */
function getRouteInfo($request){
    return [
        'app_name' => strtolower(app()->http->getName()), //应用名称
        'controller' => strtolower($request->controller()), //控制器名称
        'action' => strtolower($request->action()), //操作名称
    ];
}


