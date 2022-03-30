<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\middleware;

use app\vuecmf\model\ModelConfig;
use app\vuecmf\model\facade\Admin as AdminService;
use Closure;
use tauthz\facade\Enforcer;
use think\Exception;
use think\Request;
use think\response\Json;

/**
 * 访问权限控制中间件
 * Class Auth
 * @package app\vuecmf\middleware
 */
class Auth
{
    /**
     * 访问权限检测
     * @param Request $request
     * @param Closure $next
     * @return mixed|Json
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token = strtolower(trim($request->header('token',''),'\t\r\n?'));
            $login_info = AdminService::isLogin($token);

            if(!$login_info){
                throw new Exception('您还没有登录或登录已超时!');
            }

            $res = true;
            $routeInfo = getRouteInfo($request); //获取当前动作路径

            if($login_info['is_super'] != 10){
                $res = Enforcer::enforce($login_info['username'], $routeInfo['app_name'] , $routeInfo['controller'], $routeInfo['action']);
            }

            if(!$res){
                throw new Exception('您没有访问权限!');
            }else if($routeInfo['controller'] == 'index' && $routeInfo['action'] == 'index'){
                //后台默认首页
                return $next($request);
            }else{
                //根据当前路由找到当前的模型
                $model_name = '';
                $model_config = ModelConfig::field('id, is_tree, label')->where('table_name', $routeInfo['controller'])->where('status', 10)->find();
                !empty($model_config['id']) && $model_name = $request->controller();
                !empty($model_name) && $model_name = "\\app\\vuecmf\\model\\" . $model_name;

                $request->model_id = $model_config['id'];
                $request->model_name = $model_name;
                $request->model_label = $model_config['label'];
                $request->model_is_tree = $model_config['is_tree'];
                $request->app_name = $routeInfo['app_name'];
                $request->login_user_info = $login_info;

                return $next($request);
            }

        }catch (\Exception $e){
            return ajaxFail($e, 1003);
        }

    }
}
