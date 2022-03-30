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

use app\vuecmf\ConstConf;
use app\vuecmf\model\Admin;
use app\vuecmf\model\facade\GrantAuth;
use app\vuecmf\model\Roles;
use tauthz\facade\Enforcer;
use think\Exception;
use think\facade\Cache;
use think\facade\Db;
use think\Request;

/**
 * 管理员事件
 * Class AdminEvent
 * @package app\vuecmf\subscribe
 */
class AdminEvent extends BaseEvent
{

    /**
     * 登录
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function onLogin(Request $request): array
    {
        $data = $request->post('data',[]);
        if(empty($data['login_name'])) throw new Exception('登录名称不能为空');
        if(empty($data['password'])) throw new Exception('登录密码不能为空');

        $login_times_cache_key = 'vuecmf:login_err_times:' . $data['login_name'];

        $login_err_times = Cache::get($login_times_cache_key, 0);
        if($login_err_times > 5) throw new Exception('连续登录失败已超过6次，请过两小时后重试！');

        $adminInfo = Admin::where('username|email|mobile', $data['login_name'])
            ->field('id, username, password, is_super')
            ->where('status', 10)
            ->find();

        //设置登录失败次数，超过则不允许登录，两小时后重试
        if(empty($adminInfo) || !password_verify($data['password'], $adminInfo->password)){
            Cache::tag(ConstConf::C_TAG_USER)->set($login_times_cache_key, $login_err_times + 1, 60);
            throw new Exception('错误的登录名称或密码！请检查是否输入有误。');
        }

        $login_ip = request()->ip();
        $login_time = date('Y-m-d H:i:s');
        $token = makeToken($adminInfo->username, $adminInfo->password, $login_ip);

        $res = Admin::where('id', $adminInfo->id)
            ->update([
                'last_login_time' => $login_time,
                'last_login_ip' => $login_ip,
                'token' => $token
            ]);

        if(!$res) throw new Exception('登录出现异常！请稍后重试。');

        $mysql = Db::query('select version() as v;');

        if($adminInfo->is_super == 10){
            $role_str = '超级管理员';
        }else{
            $role_arr = Enforcer::getRolesForUser($adminInfo->username, strtolower(app()->http->getName()));
            $role_str = implode(',', $role_arr);
        }


        return [
            'token' => $token,
            'user' => [
                'username' => $adminInfo->username,
                'role' => $role_str,
                'last_login_time' => $login_time,
                'last_login_ip' => $login_ip,
            ],
            'server'=> [
                'version' => '2.0.0',
                'os' => PHP_OS,
                'software'=> $_SERVER['SERVER_SOFTWARE'],
                'mysql' => $mysql[0]['v'],
                'upload_max_size' => ini_get('upload_max_filesize')
            ]
        ];

    }

    /**
     * 退出
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function onLogout(Request $request)
    {
        $data = $request->post('data',[]);
        if(empty($data['token'])) throw new Exception('参数token缺失！');

        Admin::where('token', $data['token'])
            ->where('status', 10)
            ->update(['token' => '']);

        //清除系统缓存
        Cache::tag(ConstConf::C_TAG_USER)->clear();

        return true;
    }

    /**
     * 添加用户角色
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function onAddRole(Request $request): bool
    {
        $data = $request->post('data',[]);
        if(empty($data['username']) || empty($data['role_id_list'])) throw new Exception('参数username(用户名)和role_id_list(角色ID)不能为空');
        $role_name_list = Roles::whereIn('id', $data['role_id_list'])->column('role_name');
        return GrantAuth::roles('add', $data['username'], $role_name_list, $data['app_name']);
    }

    /**
     * 删除用户角色
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function onDelRole(Request $request): bool
    {
        $data = $request->post('data',[]);
        if(empty($data['username']) || empty($data['role_name'])) throw new Exception('参数username(用户名)和role_name(角色名称)不能为空！');
        return GrantAuth::roles('del', $data['username'], $data['role_name'], $data['app_name']);
    }

    /**
     * 添加用户权限
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function onAddPermission(Request $request): bool
    {
        $data = $request->post('data',[]);
        if(empty($data['username'])) throw new Exception('参数username(用户名)不能为空！');
        if(empty($data['action_id'])) throw new Exception('请选择功能项！');
        return GrantAuth::permission('user_add', $data['username'], $data['action_id']);
    }

    /**
     * 删除用户权限
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function onDelPermission(Request $request): bool
    {
        $data = $request->post('data',[]);
        if(empty($data['username'])) throw new Exception('参数username(用户名)不能为空！');
        if(empty($data['action_id'])) throw new Exception('请选择功能项！');
        return GrantAuth::permission('user_del', $data['username'], $data['action_id']);
    }

    /**
     * 获取用户所有权限
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function onGetPermission(Request $request): array
    {
        $data = $request->post('data',[]);
        if(empty($data['username'])) throw new Exception('参数username(用户名)不能为空！');
        $app_name = $data['app_name'] ?? 'vuecmf';
        return GrantAuth::getPermission($data['username'], $app_name, $request->login_user_info);
    }


    /**
     * 获取所有角色
     * @return array
     */
    public function onGetAllRoles(): array
    {
        return Roles::field('id `key`, role_name label, false disabled')->where('status', 10)
            ->select()->toArray();
    }

    /**
     * 获取用户下所有角色
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function onGetRoles(Request $request): array
    {
        $data = $request->post('data',[]);
        if(empty($data['username'])) throw new Exception('参数username(用户名称)不能为空！');

        $roles_list = GrantAuth::getRoles($data['username'], $data['app_name']);

        return Roles::whereIn('role_name', $roles_list)
            ->where('status', 10)
            ->column('id');
    }

    /**
     * 设置用户权限
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function onSetUserPermission(Request $request): bool
    {
        $data = $request->post('data',[]);
        if(empty($data['username'])) throw new Exception('参数username(用户名称)不能为空！');
        if(empty($data['action_id'])) throw new Exception('请选择功能项！');
        return GrantAuth::permission('user_add', $data['username'], $data['action_id']);
    }

    /**
     * 获取用户权限列表
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function onGetUserPermission(Request $request): array
    {
        $data = $request->post('data',[]);
        if(empty($data['username'])) throw new Exception('参数username(用户名称)不能为空！');
        return GrantAuth::getPermission($data['username'], $data['app_name']);
    }

}
