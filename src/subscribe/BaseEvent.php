<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\subscribe;

use app\vuecmf\model\facade\GrantAuth;
use app\vuecmf\model\facade\ModelField as ModelFieldService;
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;
use app\vuecmf\model\ModelConfig;
use app\vuecmf\model\ModelField;
use think\Exception;
use think\Request;

/**
 * 基础事件抽象类
 * Class BaseEvent
 * @package app\vuecmf\subscribe
 */
abstract class BaseEvent
{

    public $eventPrefix = ''; //事件前缀

    public function __construct()
    {
        $this->eventPrefix = app()->request->controller();
    }

    /**
     * 列表
     * @param Request $request
     * @return mixed
     */
    public function onIndex(Request $request)
    {
        $data = $request->post('data', []); //接收提交的数据
        $model = app($request->model_name);

        $model_conf = ModelConfigService::getModelConfig($request->model_id);
        if(empty($model_conf)) throw new Exception('模型配置信息缺失');
        if(empty($model_conf['label_field_name'])) throw new Exception('模型还没有设置标题字段');

        if(isset($data['action']) && $data['action'] == 'getField'){
            //列表字段及表单相关
            $order_field = $model_conf['table_name'] == 'roles' ? '' : 'sort_num';
            return $model->getTableInfo($request->model_id, $data['filter'], $model_conf['is_tree'], $model_conf['label_field_name'], $order_field);
        }else if($model_conf['is_tree']){
            //列表数据（目录树形式）
            $order_field = $model_conf['table_name'] == 'roles' ? '' : 'sort_num';
            $res['data'] = getTreeList($model, 0, $data['keywords'], 'pid', $model_conf['label_field_name'],$order_field);
            return $res;
        }else{
            //列表数据（常规形式）
            if(!empty($data['keywords'])){
                //模糊搜索
                //先取出需模糊搜索的字段
                $filter_field_list = ModelFieldService::getFilterFields($request->model_id);
                $model = $model::where(implode('|', $filter_field_list), 'like', '%'. $data['keywords'] .'%');
            }else if(!empty($data['filter'])){
                //高级搜索
                foreach ($data['filter'] as $field => $val){
                    if($val == null || $val == '') continue;
                    if(is_string($val)){
                        $model = $model->where($field, $val);
                    }else{
                        $fieldType = ModelFieldService::getTypeByFieldName($field, $request->model_id);
                        if(in_array($fieldType, ['timestamp', 'date', 'datetime'])){
                            $model = $model->whereBetween($field, $val);
                        }else{
                            $model = $model->whereIn($field, $val);
                        }
                    }
                }
            }

            //若是非超级管理员登录时，进入管理员列表，不显示超级管理员资料
            if($model_conf['table_name'] == 'admin' && $request->login_user_info['is_super'] == 20){
                $model = $model->where('is_super', 20);
            }

            //排序
            $order_field = !empty($data['order_field']) ? $data['order_field'] : 'id';
            $order_sort = !empty($data['order_sort']) ? $data['order_sort'] : 'desc';

            return $model->order($order_field, $order_sort)->paginate([
                'list_rows'=> $data['page_size'],
                'page' => $data['page']
            ])->jsonSerialize();
        }
    }

    /**
     * 保存
     * @param Request $request
     * @return mixed
     */
    public function onSave(Request $request)
    {
        $model = app($request->model_name);
        $data = $request->post('data',[]);
        if(!empty($data['id'])){
            if(isset($data['password']) && empty($data['password'])) unset($data['password']);
            $res = $model::update($data);
        }else{
            $res = $model::create($data);
        }
        return $res;
    }

    /**
     * 批量保存
     * @param Request $request
     * @return mixed
     */
    public function onSaveAll(Request $request)
    {
        $model = app($request->model_name);
        $data = $request->post('data','{}');
        is_string($data) && $data = json_decode($data, true);
        return $model->saveAll($data);
    }

    /**
     * 获取详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function onDetail(Request $request){
        $data = $request->post('data',[]);
        if(empty($data['id'])) throw new Exception('参数ID不能为空');
        $model = app($request->model_name);
        return $model::where('id', $data['id'])->find();
    }

    /**
     * 删除
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function onDelete(Request $request){
        $data = $request->post('data',[]);
        if(empty($data['id'])) throw new Exception('参数ID不能为空');
        $model = app($request->model_name, [], true);
        $id_arr = explode(',', (string)$data['id']);

        if($request->model_is_tree == 10 && isset($data['children']) && count($data['children']) > 0) {
            throw new Exception('此项下面还有子项，不可删除！若要删除，请先删除其子项');
        }

        if(count($id_arr) == 1){
            $res = $model::where('id', $data['id'])->find();
            if(empty($res)) throw new Exception('没有找到删除的数据');
            return $res->delete();
        }else{
            return $model::whereIn('id', $id_arr)->delete();
        }

    }

    /**
     * 下拉列表
     * @param Request $request
     * @return mixed
     */
    public function onDropdown(Request $request){
        $model = app($request->model_name);
        $data = $request->post('data',[]);

        if($request->model_name == '\app\vuecmf\model\ModelField'){
            isset($data['relation_model_id']) && $data['model_id'] = $data['relation_model_id'];
        }

        if(isset($data['model_id']) && empty($data['model_id'])) return [];
        if(isset($data['table_name'])){
            if(empty($data['table_name'])) return [];
            $data['model_id'] = ModelConfig::where('status', 10)
                ->where('table_name', $data['table_name'])->value('id');
        }

        $label_field_arr = ModelField::where('model_id', $request->model_id)
            ->where('is_label',10)
            ->where('status', 10)
            ->column('field_name');

        if(!empty($label_field_arr)){
            $label_field = $label_field_arr[0];
            unset($label_field_arr[0]);
            !empty($label_field_arr) && $label_field = "concat(". $label_field . ",'('," . implode(",'-',", $label_field_arr) . ",')')";
        }else{
            $label_field = 'id';
        }

        $model = $model::where('status', 10);

        if(isset($data['model_id'])){
            if(is_array($data['model_id'])){
                $model = $model->whereIn('model_id', $data['model_id']);
            }else{
                $model = $model->where('model_id', $data['model_id']);
            }
        }

        return $model->column($label_field . ' label', 'id');
    }





}
