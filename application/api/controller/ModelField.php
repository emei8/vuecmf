<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/5
 * Time: 22:58
 */

namespace app\api\controller;


use think\Exception;
use think\facade\Request;
use app\api\service\ModelFieldService;

class ModelField  extends Base
{
    protected function initialize(){
        $this->model = model('ModelField');
        $this->service = model('ModelFieldService','service');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\ModelField();
        }
    }

    /**
     * 获取关联模型选项
     * @param ModelFieldService $modelFieldService
     * @return \think\response\Json
     */
    public function getRelationOptions(ModelFieldService $modelFieldService){
        try{
            $post = input('post.');
            $options = $modelFieldService->getRelationOptions($post['field_id'],$post['filter_field'],$post['sel_val']);

            return api_return($options);

        }catch (Exception $e){
            return api_return('',$e->getMessage(),$e->getCode());
        }
    }

}