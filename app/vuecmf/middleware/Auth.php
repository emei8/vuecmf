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
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;
use tauthz\facade\Enforcer;
use think\Exception;
use think\facade\Session;

class Auth
{
    /**
     * 访问权限检测
     * @param $request
     * @param \Closure $next
     * @return mixed|\think\response\Json
     */
    public function handle($request, \Closure $next)
    {
        try{
            $login_user = Session::get('vuecmf_login_user');

            if(empty($login_user)){
                return ajaxFail('您还没有登录或登录已超时!', 1003);
            }

            $routeInfo = getRouteInfo($request); //获取当前动作路径
            $res = Enforcer::enforce($login_user, $routeInfo['app_name'] , $routeInfo['controller'], $routeInfo['action']);
            //$res = true;
            if(!$res){
                return ajaxFail('您没有访问权限!', 1004);
            }else if($routeInfo['controller'] == 'index' && $routeInfo['action'] == 'index'){
                return $next($request);
            }else{
                $api_path = '/' . $routeInfo['app_name'] . '/' . $routeInfo['controller'] . '/' . $routeInfo['action'];
                $path_arr = [$api_path, $api_path . '/'];
                if($routeInfo['action'] == 'index'){
                    $path_arr[] = '/' . $routeInfo['app_name'] . '/' . $routeInfo['controller'];
                    $path_arr[] = '/' . $routeInfo['app_name'] . '/' . $routeInfo['controller'] . '/';
                }

                //根据当前动作路径找到对应的模型
                $model_id = ModelAction::whereIn('api_path', $path_arr)->value('model_id');
                $model_name = ModelConfigService::getModelNameByModelId($model_id);

                if(empty($model_id) || empty($model_name)) throw new Exception('没有找到相应的模型！请先为当前请求创建模型动作后再重试');

                $request->model_id = $model_id;
                $request->model_name = "\\app\\vuecmf\\model\\" . $model_name;

                return $next($request);
            }

        }catch (\Exception $e){
            return ajaxFail($e->getMessage(), 1005);
        }

    }
}
