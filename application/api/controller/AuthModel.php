<?php
/**
 * Created by www.vuecmf.com
 * User: 386196596@qq.com
 * Date: 2019/1/6
 * Time: 18:58
 */

namespace app\api\controller;


use think\Exception;
use think\facade\Request;

class AuthModel extends Base
{

    protected function initialize(){
        $this->model = model('AuthModel');
        $this->service = model('AuthModelService','service');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\AuthModel();
        }

    }


    /**
     * 菜单管理列表
     * @return \think\response\Json
     */
    public function tree(){
        try{
            $keyword = input('post.keyword');
            $data = $this->service->tree($keyword);
            return api_return($data);
        }catch (Exception $e){
            return api_return('','拉取列表失败!'.$e->getMessage(),1003);
        }

    }



    /**
     * 获取模型选择列表 ID与名称对应，表单下拉框用到
     * @return \think\response\Json
     */
    public function getModel(){
        try{
            $data = $this->model->where('status',1)->column('label','id');
            return api_return($data);
        }catch (Exception $e){
            return api_return('',$e->getMessage(),$e->getCode());
        }

    }

    /**
     * 获取模型后端链接映射
     * @return \think\response\Json
     */
    public function getModelApiMap(){
        try{
            $model_data = $this->model->alias('AM')
                ->field('AM.*,MO.operate_name')
                ->join('model_operate MO','AM.main_operate_id = MO.id','LEFT')
                ->where('AM.status',1)
                ->where('MO.status',1)
                ->select();

            $data = [];
            $operate_model = model('ModelOperate');
            foreach($model_data as $val){
                $operate = $operate_model->where('model_id',$val['id'])->where('status',1)->select();
                $actions = [];
                foreach($operate as $val2){
                    $actions[$val2['operate_name']] = [
                        'label' => $val2['label'],
                        'path' => $val2['api_path']
                    ];
                }


                $data[$val['model_name']] = [
                    'label' => $val['label'],
                    'main_action' => $val['operate_name'],
                    'component' => $val['component'],
                    'actions' => $actions
                ];
            }

            return api_return($data);
        }catch (Exception $e){
            return api_return('',$e->getMessage(),$e->getCode());
        }
    }

    /**
     * 获取模型详情
     * @return \think\response\Json
     */
    public function getModelInfo(){
        try{
            $model_id = input('post.model_id');
            if(empty($model_id)) throw new Exception('模型ID缺失');
            $data = $this->model->alias('AM')
                ->field('AM.*,MO.operate_name')
                ->join('model_operate MO','AM.main_operate_id = MO.id','LEFT')
                ->where('AM.id',$model_id)
                ->where('AM.status',1)
                ->where('MO.status',1)
                ->find();

            return api_return($data);

        }catch (Exception $e){
            return api_return('',$e->getMessage(),$e->getCode());
        }
    }


}