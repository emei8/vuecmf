<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\controller;

use think\response\Json;

/**
 * 角色管理
 * Class Roles
 * @package app\vuecmf\controller
 */
class Roles extends Base
{

    /**
     * 批量分配用户
     * @return Json
     */
    public function addUsers(): Json
    {
        return self::common('AddUsers', '分配用户成功！', '分配用户失败！');
    }

    /**
     * 批量删除用户
     * @return Json
     */
    public function delUsers(): Json
    {
        return self::common('DelUsers', '删除用户成功！', '删除用户失败！');
    }

    /**
     * 添加角色权限
     * @return Json
     */
    public function addPermission(): Json
    {
        return self::common('AddPermission', '添加角色权限成功！', '添加角色权限失败！');
    }

    /**
     * 删除角色权限
     * @return Json
     */
    public function delPermission(): Json
    {
        return self::common('DelPermission', '删除角色权限成功！', '删除角色权限失败！');
    }

    /**
     * 获取角色下所有用户
     * @return Json
     */
    public function getUsers(): Json
    {
        return self::common('GetUsers', '拉取角色用户成功！');
    }

    /**
     * 获取角色下所有权限
     * @return Json
     */
    public function getPermission(): Json
    {
        return self::common('GetPermission', '拉取角色权限成功！');
    }

    /**
     * 获取所有用户
     * @return Json
     */
    public function getAllUsers(): Json
    {
        return self::common('GetAllUsers', '拉取所有用户成功！');
    }


}
