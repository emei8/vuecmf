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
class FieldOption extends Model
{

    /**
     * 获取模型字段的选项值
     * @param $model_id
     * @return array|mixed
     */
    public function getFieldOptions($model_id){
        $cache_key = 'vuecmf:field_option:field_options:' . $model_id;
        $result = Cache::get($cache_key);
        if(empty($result)){
            $data = self::field('model_field_id field_id, option_value, option_label')
                ->where('model_id', $model_id)
                ->where('status', 10)
                ->select();

            $result = [];
            foreach ($data as $val){
                $result[$val['field_id']][$val['option_value']] = $val['option_label'];
            }
            unset($data);
            Cache::tag('vuecmf')->set($cache_key, $result);
        }

        return $result;

    }


}
