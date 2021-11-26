<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\subscribe;

use app\vuecmf\model\facade\ModelForm as ModelFormService;
use app\vuecmf\model\facade\ModelField as ModelFieldService;
use app\vuecmf\model\facade\FieldOption as FieldOptionService;
use app\vuecmf\model\facade\ModelRelation as ModelRelationService;
use app\vuecmf\model\facade\ModelFormRules as ModelFormRulesService;

abstract class BaseEvent
{

    /**
     * 列表
     * @param $data
     * @return mixed
     */
    public function onIndex($data)
    {
        if(isset($data['action']) && $data['action'] == 'getField'){
            //列表字段及表单相关
            $fieldInfo = ModelFieldService::getFieldInfo($data['model_id']);
            $formInfo = ModelFormService::getFormInfo($data['model_id']);
            $fieldOption = FieldOptionService::getFieldOptions($data['model_id']);
            $relationInfo = ModelRelationService::getRelationInfo($data['model_id']);
            $formRulesInfo = ModelFormRulesService::getRuleListForForm($data['model_id']);
            return [
                'field_info' => $fieldInfo,
                'form_info' => $formInfo,
                'field_option' => $fieldOption,
                'relation_info' => $relationInfo,
                'form_rules' => $formRulesInfo,
            ];

        }else{
            //列表数据
            $model = app($data['model_name']);

            if(!empty($data['keywords'])){
                //模糊搜索
                //先取出需模糊搜索的字段
                $filter_field_list = ModelFieldService::getFilterFields($data['model_id']);
                $model = $model::where(implode('|', $filter_field_list), 'like', '%'. $data['keywords'] .'%');
            }else{
                //高级搜索
                foreach ($data as $field => $val){
                    if(empty($val) || in_array($field, ['keywords', 'limit', 'page','model_name','model_id','action'])) continue;
                    if(is_string($val)){
                        $model = $model->where($field, $val);
                    }else{
                        $fieldType = ModelFieldService::getTypeByFieldName($field, $data['model_id']);
                        if(in_array($fieldType, ['timestamp', 'date', 'datetime'])){
                            $model = $model->whereBetween($field, $val);
                        }else{
                            $model = $model->whereIn($field, $val);
                        }
                    }
                }
            }

            return $model->paginate([
                'list_rows'=> $data['limit'],
                'page' => $data['page']
            ])->jsonSerialize();
        }
    }

    /**
     * 保存
     * @param $data
     * @return bool
     */
    public function onSave($data)
    {
        return true;
    }


}