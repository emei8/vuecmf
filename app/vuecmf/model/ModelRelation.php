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
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;

/**
 * @mixin \think\Model
 */
class ModelRelation extends Model
{

    /**
     * 获取关联字段信息
     * @param $model_id
     * @return array
     */
    public function getRelationInfo($model_id): array
    {
        //先取出有关联表的字段及关联信息
        $field_info = self::alias('vmr')
            ->field('model_field_id field_id, relation_model_id, vmf.field_name relation_field_name, relation_condition, join_type, relation_show_field_id')
            ->join('vuecmf_model_field vmf', 'relation_field_id = vmf.id')
            ->where('vmr.model_id', $model_id)
            ->where('vmr.status', 10)
            ->where('vmf.status', 10)
            ->select();
        $result = [];
        foreach ($field_info as $val){
            //获取需显示的关联字段名称
            $show_field_name_arr = ModelField::whereIn('id', $val->relation_show_field_id)
                ->where('status', 10)
                ->column('field_name');
            $show_field_str = 'id';
            if(!empty($show_field_name_arr)){
                $show_field_str = $show_field_name_arr[0];
                unset($show_field_name_arr[0]);
                !empty($show_field_name_arr) && $show_field_str = "concat(". $show_field_str . ",'('," . implode(",'-',", $show_field_name_arr) . ",')')";
            }

            $relation_model = ModelConfigService::getModelInstanceByModelId($val->relation_model_id);
            $options = $relation_model::where('status', 10)
                ->column($show_field_str . ' label', $val->relation_field_name);

            //关联模型的数据列表，供表单中下拉框中使用
            $result[$val->field_id] = $options;

        }

        return $result;
    }


}
