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
use app\vuecmf\make\facade\Make;
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;
use think\facade\Cache;
use think\Model;

/**
 * 字段索引模型
 * Class ModelIndex
 * @package app\vuecmf\model
 */
class ModelIndex extends Base
{

    /**
     * 数据新增加或更新前
     * @param Model $model
     * @return mixed|void
     */
    public static function onBeforeWrite(Model $model)
    {
        is_array($model->model_field_id) && $model->model_field_id = implode(',', $model->model_field_id);
    }

    /**
     * 添加数据后
     * @param Model $model
     * @return void
     */
    public static function onAfterInsert(Model $model): void
    {
        //添加索引
        $table_name = ModelConfigService::getTableNameByModelId($model->model_id);
        $field_name_list = ModelField::field('field_name')->whereIn('id', explode(',',$model->model_field_id))->column('field_name');
        Make::addIndex($table_name, $field_name_list, $model->index_type);
    }

    /**
     * 数据更新前
     * @param Model $model
     * @return void
     */
    public static function onBeforeUpdate(Model $model): void
    {
        $table_name = ModelConfigService::getTableNameByModelId($model->model_id);
        //删除原索引
        $old_model = self::field('model_field_id, index_type')->where('id', $model->id)->find();
        $old_field_name_list = ModelField::field('field_name')->whereIn('id', explode(',',$old_model->model_field_id))->column('field_name');
        Make::delIndex($table_name, $old_field_name_list, $old_model->index_type);

        //更新索引
        $field_name_list = ModelField::field('field_name')->whereIn('id', explode(',',$model->model_field_id))->column('field_name');
        Make::addIndex($table_name, $field_name_list, $model->index_type);
    }


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

        //添加索引
        $table_name = ModelConfigService::getTableNameByModelId($model->model_id);
        $field_name_list = ModelField::field('field_name')->whereIn('id', explode(',', $model->model_field_id))->column('field_name');
        Make::delIndex($table_name, $field_name_list, $model->index_type);

    }


    /**
     * 获取模型的唯一索引字段
     * @param $model_id
     * @return array
     */
    public function getUniqueFields($model_id): array
    {
        $unique_fields = self::where('model_id', $model_id)
            ->where('index_type', 'UNIQUE')
            ->where('status', 10)
            ->column('model_field_id');

        foreach ($unique_fields as &$ids){
            $ids = ModelField::whereIn('id', $ids)
                ->where('status', 10)
                ->column('field_name, label');
        }

        return $unique_fields;

    }

}
