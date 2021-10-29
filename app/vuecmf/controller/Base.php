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

use app\BaseController;

/**
 * 需要授权访问的控制器基类
 * Class Base
 * @package app\vuecmf\controller
 */
abstract class Base extends BaseController
{
    protected $middleware = ['auth'];
}
