<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\model;

use app\vuecmf\ConstConf;
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;
use think\facade\Cache;
use think\Model;

/**
 * 字段关联模型
 * Class ModelRelation
 * @package app\vuecmf\model
 */
class ModelRelation extends Base
{

    /**
     * 数据添加或更新后
     * @param Model $model
     * @return void
     */
    public static function onAfterWrite(Model $model): void
    {
        Cache::tag(ConstConf::C_TAG_MODEL)->clear();
    }

    /**
     * 数据删除后
     * @param Model $model
     * @return void
     */
    public static function onAfterDelete(Model $model): void
    {
        Cache::tag(ConstConf::C_TAG_MODEL)->clear();
    }

    /**
     * 获取关联字段信息
     * @param int $model_id
     * @param array|null $filter
     * @return array
     */
    public function getRelationInfo(int $model_id, ?array $filter): array
    {
        $result = [
            'options' => [],
            'linkage' => []
        ];

        //联动关联字段信息, 供表单中与之相关的下拉框联动变化
        $linkage = $this->getRelationLinkage($model_id, $filter);
        !empty($linkage) && $result['linkage'] = $linkage;

        //关联模型的数据列表，供表单中下拉框中使用
        $options = $this->getRelationOptions($model_id, $filter);
        !empty($options) && $result['options'] = $options;

        //关联模型的数据列表，供列表及搜索表单下拉框中使用
        if(empty($filter['model_id'])) unset($filter['model_id']);
        $full_options = $this->getRelationOptions($model_id, $filter);
        !empty($full_options) && $result['full_options'] = $full_options;



        return $result;
    }

    /**
     * 获取关联下拉选项列表
     * @param int $model_id
     * @param array|null $filter
     * @return array
     */
    private function getRelationOptions(int $model_id, ?array $filter): array
    {
        $result = [];

        //先取出有关联表的字段及关联信息
        $field_info = self::alias('vmr')
            ->field('model_field_id field_id, relation_model_id, mc.table_name relation_table_name, vmf.field_name relation_field_name, relation_show_field_id')
            ->join('model_field vmf', 'relation_field_id = vmf.id', 'LEFT')
            ->join('model_config mc', 'mc.id = vmr.relation_model_id', 'LEFT')
            ->where('vmr.relation_field_id', '<>', 0)
            ->where('vmr.model_id', $model_id)
            ->where('vmr.status', 10)
            ->where('vmf.status', 10)
            ->select();

        $model_table_name = ModelConfig::where('id', $model_id)->where('status', 10)->value('table_name');

        foreach ($field_info as $val){
            //获取需显示的关联字段名称
            $relation_model = ModelConfigService::getModelInstanceByModelId($val->relation_model_id);
            $is_tree = ModelConfig::field('is_tree')->where('id', $val->relation_model_id)->value('is_tree');

            if($is_tree == 10){
                //若关联的模型是目录树的、则下拉选项需要格式化树型结构
                $options = [];
                formatTree($options, $relation_model);

            }else{
                $show_field_name_arr = ModelField::whereIn('id', explode(',', $val->relation_show_field_id))
                    ->where('status', 10)
                    ->column('field_name');

                if($model_table_name == 'model_form_rules' && $val->relation_table_name == 'model_form' && in_array('model_field_id', $show_field_name_arr) && in_array('type', $show_field_name_arr)){
                    $options = $relation_model::alias('A')
                        ->join('model_field F', 'F.id = A.model_field_id and F.status = 10','LEFT')
                        ->join('field_option FP', 'FP.option_value = A.type and FP.status = 10', 'LEFT')
                        ->where('A.status', 10)
                        ->where('F.status', 10)
                        ->where('FP.status', 10);

                    isset($filter['model_id']) && $options = $options->where('A.model_id', $filter['model_id']);
                    $options = $options->column('concat(F.field_name,"(",F.label,")-",FP.option_label) label', 'A.' . $val->relation_field_name);

                }else{

                    $show_field_str = 'id';
                    if(!empty($show_field_name_arr)){
                        $show_field_str = $show_field_name_arr[0];
                        unset($show_field_name_arr[0]);
                        !empty($show_field_name_arr) && $show_field_str = "concat(". $show_field_str . ",'('," . implode(",'-',", $show_field_name_arr) . ",')')";
                    }

                    $query = $relation_model::where('status', 10);
                    if(!empty($filter) && in_array($val->relation_table_name, ['model_field','model_action'])){
                        foreach ($filter as $field => $filter_val){
                            if($field == 'model_id'){
                                //取出所关联的模型ID
                                $filter_val_arr = self::field('relation_model_id')->where('model_id', $filter_val)->column('relation_model_id');
                                $filter_val_arr[] = $filter_val;
                                $query = $query->whereIn($field, $filter_val_arr);
                            }else{
                                $query = $query->where($field, $filter_val);
                            }
                        }
                    }

                    $options = $query->column($show_field_str . ' label', $val->relation_field_name);
                }

            }


            //关联模型的数据列表，供表单中下拉框中使用
            $result[$val->field_id] = $options;

        }

        return $result;
    }

    /**
     * 获取联动关联字段信息
     * @param int $model_id
     * @param array|null $filter
     * @return array
     */
    private function getRelationLinkage(int $model_id, ?array $filter): array
    {
        $result = [];

        //先取出有关联表的字段及关联信息
        $field_info = ModelFormLinkage::field('model_field_id field_id, linkage_field_id, linkage_action_id')
            ->where('model_id', $model_id)
            ->where('status', 10)
            ->select();

        foreach ($field_info as $val){
            $action_info = ModelAction::alias('MA')
                ->field('MA.action_type, MC.table_name')
                ->join('model_config MC', 'MA.model_id = MC.id', 'LEFT')
                ->where('MA.id', $val->linkage_action_id)
                ->where('MA.status', 10)
                ->where('MC.status', 10)
                ->find();

            //联动关联字段信息, 供表单中与之相关的下拉框联动变化
            $result[$val->field_id][$val->linkage_field_id] = [
                'relation_field_id' => $val->linkage_field_id,
                'action_table_name' => $action_info['table_name'],
                'action_type' => $action_info['action_type'],
            ];

        }

        return $result;
    }



}
