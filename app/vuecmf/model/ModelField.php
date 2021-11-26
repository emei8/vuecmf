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

use think\Collection;
use think\facade\Cache;
use think\Model;

/**
 * @mixin Model
 */
class ModelField extends Model
{
    /**
     * 获取可模糊搜索的字符串类型字段
     * @param $model_id
     * @return array
     */
    public function getFilterFields($model_id): array
    {
        $cache_key = 'vuecmf:model_field:filter_field:' . $model_id;
        $result = Cache::get($cache_key);
        if(empty($result)){
            $result = self::where('model_id', $model_id)
                ->whereIn('type',['varchar', 'char'])
                ->column('field_name');
            Cache::tag('vuecmf')->set($cache_key, $result);
        }
        return $result;
    }

    /**
     * 获取指定字段的类型
     * @param $field_name
     * @param $model_id
     * @return mixed
     */
    public function getTypeByFieldName($field_name, $model_id)
    {
        $cache_key = 'vuecmf:model_field:' . $model_id;
        $data = Cache::get($cache_key);
        if(empty($data[$field_name])){
            $result = self::where('model_id', $model_id)
                ->where('field_name', $field_name)
                ->value('type');
            $data[$field_name] = $result;
            Cache::tag('vuecmf')->set($cache_key, $data);
        }else{
            $result = $data[$field_name];
        }
        return $result;
    }

    /**
     * 获取字段信息
     * @param $model_id
     * @return array|Collection
     */
    public function getFieldInfo($model_id)
    {
        $cache_key = 'vuecmf:model_field:field_info:' . $model_id;
        $result = Cache::get($cache_key);
        if(empty($result)){
            $result = self::field('
                `id` `field_id`,
                `field_name` `prop`,
                `label`,
                `column_width` `width`, 
                `length`,
                `is_show` `show`,
                `is_fixed` `fixed`,
                `is_filter` `filter`,
                `note` `tooltip`,
                `model_id`,
                "true" `sortable`
            ')
                ->where('model_id', $model_id)
                ->where('status', 10)
                ->order('sort_num')
                ->select();

            Cache::tag('vuecmf')->set($cache_key, $result);
        }
        return $result;

    }



}
