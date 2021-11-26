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
 * Class ModelConfig
 * @package app\vuecmf\model\facade
 * @method static string getTableNameByModelId($model_id) 根据模型ID获取对应表名（不含前缀）
 * @method static string getModelNameByModelId($model_id) 根据模型ID获取对应模型名称
 * @method static object getModelInstanceByModelId($model_id) 根据模型ID获取对应模型实例
 */
class ModelConfig extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return 'app\vuecmf\model\ModelConfig';
    }
}