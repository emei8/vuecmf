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
class ModelFormRules extends Model
{
    /**
     * 根据模型ID获取模型表单的校验规则
     * @param $model_id
     * @return mixed
     */
    public function getRuleListByModel($model_id)
    {
        return self::alias('vmfr')
            ->field('vmf2.field_name, vmfr.rule_type, vmfr.rule_value, vmfr.error_tips')
            ->join('model_form vmf', 'vmfr.model_form_id = vmf.id', 'LEFT')
            ->join('model_field vmf2', 'vmf.model_field_id = vmf2.id', 'LEFT')
            ->where('vmfr.model_id', $model_id)
            ->where('vmfr.status', 10)
            ->where('vmf.status', 10)
            ->where('vmf2.status', 10)
            ->order('vmfr.model_form_id, vmfr.id')
            ->select();
    }


    /**
     * 获取模型表单的数据验证规则（前端使用）
     * @param $model_id
     * @return array
     */
    public function getRuleListForForm($model_id): array
    {
        $data = self::alias('vmfr')
            ->field('vmf2.field_name, rule_type, rule_value, error_tips')
            ->join('vuecmf_model_form vmf', 'vmfr.model_form_id = vmf.id', 'LEFT')
            ->join('vuecmf_model_field vmf2', 'vmf.model_field_id = vmf2.id')
            ->whereIn('rule_type',['require','length','date','email'])
            ->where('vmfr.model_id', $model_id)
            ->where('vmfr.status', 10)
            ->where('vmf.status', 10)
            ->where('vmf2.status', 10)
            ->select();
        $result = [];
        foreach ($data as $val){
            switch ($val->rule_type){
                case 'require':
                    $result[$val->field_name][] = [
                        'required' => true,
                        'message' => $val->error_tips,
                        'trigger' => 'blur'
                    ];
                    break;
                case 'length':
                    $arr = explode(',', $val->rule_value);
                    $result[$val->field_name][] = [
                        'min' => intval($arr[0]) ?? 0,
                        'max' => intval($arr[1]) ?? 0,
                        'message' => $val->error_tips,
                        'trigger' => 'blur'
                    ];
                    break;
                case 'date':
                    $result[$val->field_name][] = [
                        'type' => 'date',
                        'required' => true,
                        'message' => $val->error_tips,
                        'trigger' => ['blur', 'change']
                    ];
                    break;
                case 'email':
                    $result[$val->field_name][] = [
                        'type' => 'email',
                        'required' => true,
                        'message' => $val->error_tips,
                        'trigger' => ['blur', 'change']
                    ];
                    break;
            }
        }

        return $result;

    }



}
