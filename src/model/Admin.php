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
use think\Exception;
use think\facade\Cache;
use think\Model;

/**
 * 管理员模型
 * Class Admin
 * @package app\vuecmf\model
 */
class Admin extends Base
{
    /**
     * 密码加密
     * @param $value
     * @return false|string|null
     */
    public function setPasswordAttr($value)
    {
        if(empty($value)) return false;
        return password_hash($value,PASSWORD_DEFAULT);
    }

    /**
     * 新增前数据处理
     * @param Model $model
     */
    public static function onBeforeInsert(Model $model)
    {
        $model->reg_ip = request()->ip();
    }

    /**
     * 更新前数据处理
     * @param Model $model
     */
    public static function onBeforeUpdate(Model $model)
    {
        $model->update_time = date('Y-m-d H:i:s');
        $model->last_login_time = $model->update_time;
        $model->last_login_ip = request()->ip();
    }

    /**
     * 删除用户后处理
     * @param Model $model
     */
    public static function onAfterDelete(Model $model)
    {
        //清除用户相关权限
        Enforcer::deleteUser($model->username);
    }


    /**
     * 判断是否已登录
     * @param $token
     * @return array|bool
     * @throws Exception
     */
    public function isLogin($token)
    {
        if(empty($token)) return false;

        $cache_key = 'vuecmf:login_user_info:' . $token;
        $login_info = Cache::get($cache_key);

        if(empty($login_info)){
            $adminInfo = self::field('username, password, is_super, token')
                ->where('token', $token)
                ->where('status', 10)
                ->find();
            if(empty($adminInfo) || $token != makeToken($adminInfo->username, $adminInfo->password, request()->ip())){
                return false;
            }
            $login_info = [
                'username' => $adminInfo->username,
                'is_super' => $adminInfo->is_super,
                'token'    => $adminInfo->token,
            ];
            Cache::tag(ConstConf::C_TAG_USER)->set($cache_key, $login_info, 3600);
        }

        return $login_info;

    }

}
