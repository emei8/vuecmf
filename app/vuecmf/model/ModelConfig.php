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


use think\facade\Cache;
use think\Model;

/**
 * @mixin \think\Model
 */
class ModelConfig extends Model
{

    /**
     * 根据模型ID获取对应表名（不含前缀）
     * @param $model_id
     * @return mixed
     */
    public function getTableNameByModelId($model_id)
    {
        $cache_key = 'vuecmf:model_config:table_name:'.$model_id;
        $table_name = Cache::get($cache_key);
        if(empty($table_name)){
            $table_name = ModelConfig::where('id', $model_id)->value('table_name');
            Cache::tag('vuecmf')->set($cache_key, $table_name);
        }
        return $table_name;
    }

    /**
     * 根据模型ID获取对应模型名称
     * @param $model_id
     * @return array|string|string[]
     */
    public function getModelNameByModelId($model_id)
    {
        $table_name = self::getTableNameByModelId($model_id);
        return str_replace(' ','', ucwords(str_replace('_', ' ', $table_name)));
    }

    /**
     * 根据模型ID获取对应模型实例
     * @param $model_id
     * @return object|\think\App
     */
    public function getModelInstanceByModelId($model_id)
    {
        $model_name = self::getModelNameByModelId($model_id);
        return app("\\app\\vuecmf\\model\\" . $model_name);
    }



}
