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
                return ajaxReturn([], '您还没有登录或登录已超时!', 1000);
            }

            $app_name = strtolower(app()->http->getName()); //应用名称
            $controller = strtolower($request->controller()); //控制器名称
            $action = strtolower($request->action()); //操作名称

            $res = Enforcer::enforce($login_user, $app_name , $controller, $action);
            if(!$res){
                return ajaxReturn([], '您没有访问权限!', 1001);
            }else{
                return $next($request);
            }
        }catch (Exception $e){
            return ajaxReturn([], $e->getMessage(), 1002);
        }

    }
}
