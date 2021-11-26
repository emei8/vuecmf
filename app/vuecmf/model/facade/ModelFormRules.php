<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\model\facade;


use think\Facade;

/**
 * Class ModelFormRules
 * @package app\vuecmf\model\facade
 * @method static array getRuleListByModel($model_id) 根据模型ID获取模型表单的校验规则
 * @method static array getRuleListForForm($model_id) 获取模型表单的数据验证规则（前端使用）
 */
class ModelFormRules extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return 'app\vuecmf\model\ModelFormRules';
    }

}