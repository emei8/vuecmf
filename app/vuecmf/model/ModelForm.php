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
class ModelForm extends Model
{

    /**
     * 获取模型的表单信息
     * @param $model_id
     * @return mixed
     */
    public function getFormInfo($model_id)
    {
        $cache_key = 'vuecmf:model_form:form_info:' . $model_id;
        $result = Cache::get($cache_key);
        if(empty($result)){
            $result = self::alias('vmf')
                ->field('vmf.model_field_id field_id, vmf2.field_name, vmf2.label, vmf.`type`, vmf.default_value, vmf.is_disabled')
                ->join('vuecmf_model_field vmf2', 'vmf.model_field_id = vmf2.id')
                ->where('vmf.model_id', $model_id)
                ->where('vmf.status', 10)
                ->where('vmf2.status', 10)
                ->order('vmf.sort_num')
                ->select();
            Cache::tag('vuecmf')->set($cache_key, $result);
        }

        return $result;
    }


}
