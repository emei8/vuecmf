<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\make\facade;

use think\Facade;

/**
 * Class Make
 * @package app\vuecmf\make\facade
 * @method static void buildModelClass(string $table_name, string $table_label = '', bool $is_tree = false) 生成模型的相关类文件
 * @method static void buildClass(string $type, string $table_name, string $table_label = '') 生成类文件
 * @method static void removeModelClass(string $table_name) 移除模型相关类文件
 * @method static void removeClass(string $type, string $table_name) 移除类文件
 * @method static void buildModelData(int $model_id, string $table_name, string $label, int $is_tree, string $remark = '') 生成模型初始数据
 * @method static void removeModelData(int $model_id, string $table_name) 移除模型相关数据
 * @method static void renameTable(string $old_table_name, string $new_table_name) 更新表名
 * @method static void addField(string $table_name, string $field_name, string $type = null, array $options = []) 添加表字段
 * @method static void renameField(string $table_name, string $old_field_name, string $new_field_name) 重命名字段名
 * @method static void delField(string $table_name, string $field_name) 删除字段
 * @method static void addIndex(string $table_name, array $field_name_list, $type = 'NORMAL') 添加索引
 * @method static void delIndex(string $table_name, array $field_name_list, string $type = 'NORMAL') 删除索引
 */
class Make extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return 'app\vuecmf\make\Make';
    }

}
