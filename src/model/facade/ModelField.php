<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\model\facade;


use think\Facade;

/**
 * 模型字段模型
 * Class ModelField
 * @package app\vuecmf\model\facade
 * @method static array getFilterFields($model_id) 获取可模糊搜索的字符串类型字段
 * @method static string getTypeByFieldName($field_name, $model_id) 获取指定字段的类型
 * @method static array getFieldInfo($model_id) 获取字段信息
 */
class ModelField extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return 'app\vuecmf\model\ModelField';
    }

}