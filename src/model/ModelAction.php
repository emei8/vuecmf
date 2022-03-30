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
use tauthz\facade\Enforcer;
use think\facade\Cache;
use think\Model;

/**
 * 模型动作模型
 * Class ModelAction
 * @package app\vuecmf\model
 */
class ModelAction extends Base
{
    /**
     * 更新动作前
     * @param Model $model
     * @return mixed|void
     */
    public static function onBeforeUpdate(Model $model)
    {
        //清除相关权限项
        $old_api_path = self::where('id', $model->id)->value('api_path');
        $arr = explode('/', trim($old_api_path));
        if(count($arr) == 2){
            Enforcer::deletePermission($arr[0], $arr[1], 'index');
        }else if(count($arr) == 3){
            Enforcer::deletePermission($arr[0], $arr[1], $arr[2]);
        }
    }

    /**
     * 删除动作后
     * @param Model $model
     */
    public static function onAfterDelete(Model $model)
    {
        //清除相关权限项
        $arr = explode('/', trim($model->api_path));
        if(count($arr) == 2){
            Enforcer::deletePermission($arr[0], $arr[1], 'index');
        }else if(count($arr) == 3){
            Enforcer::deletePermission($arr[0], $arr[1], $arr[2]);
        }

        Cache::tag(ConstConf::C_TAG_MODEL)->clear();
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
     * 获取所有指定的API映射列表
     * @param $api_id_arr
     * @return array
     */
    public function getAllApiMap($api_id_arr): array
    {
        $res = ModelAction::alias('ma')
            ->field('mc.table_name, ma.action_type, ma.api_path')
            ->join('model_config mc',' ma.model_id = mc.id')
            ->whereIn('ma.id', $api_id_arr)
            ->select();

        $arr = [];
        foreach ($res as $val){
            $arr[$val['table_name']][$val['action_type']] = $val['api_path'];
        }
        return $arr;
    }



}
