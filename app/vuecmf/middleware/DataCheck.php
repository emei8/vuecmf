<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\middleware;

use app\vuecmf\model\ModelAction;
use think\Exception;
use think\exception\ValidateException;
use think\Response;

/**
 * 数据校验
 * Class DataCheck
 * @package app\vuecmf\middleware
 */
class DataCheck
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {

        try{
            //获取当前动作路径
            $app_name = strtolower(app()->http->getName()); //应用名称
            $controller = strtolower($request->controller()); //控制器名称
            $action = strtolower($request->action()); //操作名称
            $api_path = '/' . $app_name . '/' . $controller . '/' . $action;
            $path_arr = [$api_path, $api_path . '/'];
            if($action == 'index'){
                $path_arr[] = '/' . $app_name . '/' . $controller;
                $path_arr[] = '/' . $app_name . '/' . $controller . '/';
            }

            //根据当前动作路径找到对应的模型
            $model_id = ModelAction::whereIn('api_path', $path_arr)->value('model_id');

            //dump($model_id);


            if(!$request->isPost()) throw new Exception('Request method error!');
            $data = $request->post();
            //$res = validate(User::class)->batch(true)->check($data);

            $rule = [
                'username'  => 'require|max:25',
                'age'   => 'number|between:1,120',
                'email' => 'email',
            ];

            $message = [
                'username.require' => '名称必须',
                'username.max'     => '名称最多不能超过25个字符',
                'age.number'   => '年龄必须是数字',
                'age.between'  => '年龄只能在1-120之间',
                'email.email'        => '邮箱格式错误',
            ];

            //validate($rule, $message)->batch(true)->check($data['data']);

            return $next($request);

        }catch (ValidateException $e){
            return ajaxFail($e->getMessage(), 1001);
        }catch (\Exception $e){
            return ajaxFail($e->getMessage(), 1002);
        }

    }
}
