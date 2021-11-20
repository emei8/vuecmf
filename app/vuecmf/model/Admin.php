<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Admin extends Model
{
    public function setPasswordAttr($value)
    {
        return password_hash($value,PASSWORD_DEFAULT);
    }

}
