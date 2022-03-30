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
 * 模型配置模型
 * Class ModelConfig
 * @package app\vuecmf\model\facade
 * @method static string getTableNameByModelId($model_id) 根据模型ID获取对应表名（不含前缀）
 * @method static string getModelNameByModelId($model_id) 根据模型ID获取对应模型名称
 * @method static object getModelInstanceByModelId($model_id) 根据模型ID获取对应模型实例
 * @method static array getModelConfig(int $model_id) 获取指定模型的配置信息
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