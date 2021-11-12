<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\controller;

use think\Request;

/**
 * 模型管理
 * Class Model
 * @package app\vuecmf\controller
 */
class Model extends Base
{
    /**
     * 模型管理列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        echo 'model list';
    }

    /**
     * 保存模型
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }


    /**
     * 删除模型
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
