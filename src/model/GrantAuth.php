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
use app\vuecmf\model\facade\Admin as AdminService;
use tauthz\facade\Enforcer;
use think\Exception;
use think\facade\Cache;
use think\facade\Db;

/**
 * 权限管理模型
 * Class GrantAuth
 * @package app\vuecmf\model
 */
class GrantAuth
{
    /**
     * 添加/删除用户角色
     * @param string $method  add | del
     * @param string $username 用户名
     * @param array|string $role_name_list 角色名称
     * @param string $app_name  应用名称
     * @return bool
     */
    public function roles(string $method, string $username, $role_name_list, string $app_name = 'vuecmf'): bool
    {
        is_string($role_name_list) && $role_name_list = [$role_name_list];
        empty($app_name) && $app_name = 'vuecmf';

        Db::startTrans();
        try{
            switch ($method){
                case 'add':
                    $method_name = 'addRoleForUser';
                    break;
                case 'del':
                    $method_name = 'deleteRoleForUser';
                    break;
                default:
                    return false;
            }
            foreach ($role_name_list as $role_name){
                Enforcer::$method_name($username, $role_name, $app_name);
            }
            Db::commit();
            return true;
        }catch (Exception $e){
            Db::rollback();
            return false;
        }

    }

    /**
     * 给角色分配用户
     * @param string $method add | del
     * @param array|string $username_list  用户名列表 | 用户名
     * @param string $role_name  角色名称
     * @param string $app_name  应用名称
     * @return bool
     */
    public function users(string $method, $username_list, string $role_name, string $app_name = 'vuecmf'): bool
    {
        is_string($username_list) && $username_list = [$username_list];
        empty($app_name) && $app_name = 'vuecmf';
        Db::startTrans();
        try{
            switch ($method){
                case 'add':
                    $method_name = 'addRoleForUser';
                    break;
                case 'del':
                    $method_name = 'deleteRoleForUser';
                    break;
                default:
                    return false;
            }
            foreach ($username_list as $username){
                Enforcer::$method_name($username, $role_name, $app_name);
            }
            Db::commit();
            return true;
        }catch (Exception $e){
            Db::rollback();
            return false;
        }
    }

    /**
     * 添加/删除(用户或角色)权限
     * @param string $method user_add | user_del | role_add | role_del
     * @param string $user_or_role  用户名称 或 角色名称
     * @param string $action_id  动作ID,多个用英文逗号分隔
     * @return bool
     */
    public function permission(string $method, string $user_or_role, string $action_id): bool
    {
        $action_path = ModelAction::whereIn('id', $action_id)
            ->where('status', 10)
            ->column('api_path');
        Db::startTrans();
        try{
            switch ($method){
                case 'user_add':
                case 'role_add':
                    Enforcer::deletePermissionsForUser($user_or_role); //先清除原有权限
                    $method_name = 'addPermissionForUser';
                    break;
                case 'user_del':
                case 'role_del':
                    $method_name = 'deletePermissionForUser';
                    break;
                default:
                    return false;
            }
            foreach ($action_path as $path){
                $arr = explode('/', trim($path,'/'));
                if(count($arr) < 2) continue;
                $app_name = $arr[0];
                $controller = $arr[1];
                $action = $arr[2] ?? 'index';
                Enforcer::$method_name($user_or_role, $app_name, $controller, $action);
            }
            Db::commit();

            //清除权限缓存
            Cache::tag(ConstConf::C_TAG_USER)->clear();

            return true;
        }catch (Exception $e){
            Db::rollback();
            return false;
        }
    }

    /**
     * 获取(用户或角色)所有权限ID列表
     * @param string $user_or_role 用户名或角色名称
     * @param string $app_name 应用名称
     * @param null $login_user_info 仅针对登录用户的权限获取
     * @param null $model_label 模型标签
     * @return array
     */
    public function getPermission(string $user_or_role, string $app_name = 'vuecmf', $login_user_info = null, $model_label = null): array
    {
        if(empty($user_or_role)) return [];
        empty($app_name) && $app_name = 'vuecmf';

        $cacke_key = 'vuecmf:permission:' . $app_name . ':' . $user_or_role;
        $res = Cache::get($cacke_key, []);

        if(empty($res)){
            if(!empty($login_user_info) && $login_user_info['is_super'] == 10){
                //超级管理员拥有所有权限
                $action_list = ModelAction::alias('MA')
                    ->join('model_config MC', 'MA.model_id = MC.id','LEFT')
                    ->where('MA.status', 10)
                    ->where('MC.status', 10)
                    ->column('MA.id, MC.label');

                foreach ($action_list as $item){
                    $res[$item['label']][] = (string)$item['id'];
                }

            }else{
                $data = Enforcer::getImplicitPermissionsForUser($user_or_role, $app_name);

                $path_list = [];  //API请求地址列表
                $n = 0;
                foreach ($data as $val){
                    array_push($path_list, '/' . $val[1] . '/' . $val[2] . '/' . $val[3]);
                    $val[3] == 'index' && array_push($path_list, '/' . $val[1] . '/' . $val[2]);
                    $n ++;
                    if($n % 100 == 0){
                        $action_list = ModelAction::alias('MA')
                            ->join('model_config MC', 'MA.model_id = MC.id','LEFT')
                            ->whereIn('MA.api_path', $path_list)
                            ->where('MA.status', 10)
                            ->where('MC.status', 10)
                            ->column('MA.id, MC.label');
                        foreach ($action_list as $item){
                            $res[$item['label']][] = (string)$item['id'];
                        }
                        $path_list = [];
                    }
                }

                if(!empty($path_list)){
                    $action_list = ModelAction::alias('MA')
                        ->join('model_config MC', 'MA.model_id = MC.id','LEFT')
                        ->whereIn('MA.api_path', $path_list)
                        ->where('MA.status', 10)
                        ->where('MC.status', 10)
                        ->column('MA.id, MC.label');
                    foreach ($action_list as $item){
                        $res[$item['label']][] = (string)$item['id'];
                    }
                }
            }

            Cache::tag(ConstConf::C_TAG_USER)->set($cacke_key, $res);
        }

        return !empty($model_label) ? $res[$model_label] : $res;
    }

    /**
     * 获取角色下所有用户名称
     * @param string $role_name  角色名称
     * @param string $app_name  应用名称
     * @return array
     */
    public function getUsers(string $role_name, string $app_name = 'vuecmf'): array
    {
        if(empty($role_name)) return [];
        empty($app_name) && $app_name = 'vuecmf';
        return Enforcer::getUsersForRole($role_name, $app_name);
    }


    /**
     * 获取用户下所有角色名称
     * @param string $username  用户名称
     * @param string $app_name  应用名称
     * @return array
     */
    public function getRoles(string $username, string $app_name = 'vuecmf'): array
    {
        if(empty($username)) return [];
        empty($app_name) && $app_name = 'vuecmf';
        return Enforcer::getRolesForUser($username, $app_name);
    }

}