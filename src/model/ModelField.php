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
use think\Collection;
use think\facade\Cache;
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;
use think\Model;

/**
 * 模型字段模型
 * Class ModelField
 * @package app\vuecmf\model
 */
class ModelField extends Base
{
    /**
     * 数据添加后
     * @param Model $model
     * @return void
     */
    public static function onAfterInsert(Model $model): void
    {
        //给对应表添加字段
        $table_name = ModelConfigService::getTableNameByModelId($model->model_id);

        $type_maps = [
            'bigint' => 'integer',
            'char' => 'string',
            'date' => 'date',
            'datetime' => 'datetime',
            'decimal' => 'decimal',
            'double' => 'float',
            'float' => 'float',
            'int' => 'integer',
            'longtext' => 'text',
            'mediumtext' => 'text',
            'smallint' => 'integer',
            'text' => 'text',
            'timestamp' => 'timestamp',
            'tinyint' => 'integer',
            'varchar' => 'string',
        ];

        if(in_array($model->getData('type'), ['bigint','int','smallint','tinyint'])){
            $model->default_value = intval($model->default_value);
        }else if(in_array($model->getData('type'), ['decimal','double','float'])){
            $model->default_value = floatval($model->default_value);
        }else if(in_array($model->getData('type'), ['longtext','mediumtext','text'])){
            $model->default_value = null;
        }


        Make::addField($table_name, $model->field_name, $type_maps[$model->getData('type')], [
            'limit' => $model->length,
            'default' => $model->default_value,
            'null' => $model->is_null == 10,
            'precision' => $model->length,
            'scale' => $model->decimal_length,
            'comment' => $model->note,
            'signed' => $model->is_signed == 10,
        ]);
    }


    /**
     * 数据更新前
     * @param Model $model
     * @return mixed|void
     */
    public static function onBeforeUpdate(Model $model)
    {
        $old_field_name = self::field('field_name')->where('id', $model->id)->value('field_name');
        //若原字段名与新字段名不一致，则更新表字段名
        if($old_field_name != $model->field_name){
            $table_name = ModelConfigService::getTableNameByModelId($model->model_id);
            Make::renameField($table_name, $old_field_name, $model->field_name);
        }
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

        //删除表中对应字段
        $table_name = ModelConfigService::getTableNameByModelId($model->model_id);
        Make::delField($table_name, $model->field_name);

    }

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
            Cache::tag(ConstConf::C_TAG_MODEL)->set($cache_key, $result);
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
            Cache::tag(ConstConf::C_TAG_MODEL)->set($cache_key, $data);
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
                10 `sortable`
            ')
                ->where('model_id', $model_id)
                ->where('status', 10)
                ->order('sort_num')
                ->select();
            foreach ($result as &$val){
                $val['show'] = $val['show'] == 10;
                $val['fixed'] = $val['fixed'] == 10;
                $val['filter'] = $val['filter'] == 10;
                $val['sortable'] = $val['sortable'] == 10;
            }


            Cache::tag(ConstConf::C_TAG_MODEL)->set($cache_key, $result);
        }
        return $result;

    }



}
