<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\controller;

use think\response\Json;

/**
 * 菜单管理
 * Class Menu
 * @package app\vuecmf\controller
 */
class Menu extends Base
{

    /**
     * 导航菜单
     */
    public function nav(): Json
    {
        return self::common('Nav', '拉取成功！', '拉取失败！');
    }


}
