<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------

/**
 * ajax请求返回的数据结构
 * @param array $data
 * @param string $msg
 * @param int $code
 * @return \think\response\Json
 */
function ajaxReturn($data = [], $msg = '', $code = 0){
    return json([
        'data'  => $data,
        'msg'   => $msg,
        'code'  => $code
    ]);
}




