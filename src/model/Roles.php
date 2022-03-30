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

use tauthz\facade\Enforcer;
use think\Model;

/**
 * 角色模型
 * Class Roles
 * @package app\vuecmf\model
 */
class Roles extends Base
{

    /**
     * 新增加或更新前
     * @param Model $model
     * @return mixed|void
     */
    public static function onBeforeWrite(Model $model)
    {
        $id_path = $model->pid;
        getTreeIdPath($id_path, $model, $id_path);

        $model->id_path = $id_path;
    }


    /**
     * 更新角色前
     * @param Model $model
     * @return mixed|void
     */
    public static function onBeforeUpdate(Model $model)
    {
        //清除角色相关权限
        $old_role_name = self::where('id', $model->id)->value('role_name');
        Enforcer::deleteRole($old_role_name);
    }

    /**
     * 删除角色后
     * @param Model $model
     */
    public static function onAfterDelete(Model $model)
    {
        //清除角色相关权限
        Enforcer::deleteRole($model->role_name);
    }

}
