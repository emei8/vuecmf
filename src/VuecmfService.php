<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf;

use think\Service;
use app\vuecmf\command\Publish;

class VuecmfService extends Service
{
    public function register()
    {

    }

    public function boot()
    {
        $this->commands(['vuecmf:publish' => Publish::class]);
    }

}