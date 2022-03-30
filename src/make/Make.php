<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\make;


use app\vuecmf\model\FieldOption;
use app\vuecmf\model\Menu;
use app\vuecmf\model\ModelAction;
use app\vuecmf\model\ModelConfig;
use app\vuecmf\model\facade\ModelConfig as ModelConfigService;
use app\vuecmf\model\ModelField;
use app\vuecmf\model\ModelForm;
use app\vuecmf\model\ModelFormLinkage;
use app\vuecmf\model\ModelFormRules;
use app\vuecmf\model\ModelIndex;
use app\vuecmf\model\ModelRelation;
use Phinx\Db\Adapter\AdapterFactory;
use think\Exception;
use think\migration\Migrator;

/**
 * 模型类文件生成及数据初始化相关
 * Class Make
 * @package app\vuecmf\make
 */
class Make
{
    /**
     * 生成模型的相关类文件
     * @param string $table_name    模型表名（不含前缀）
     * @param string $table_label   模型标签
     * @param bool $is_tree         是否目录树
     * @return void
     * @throws Exception
     */
    public function buildModelClass(string $table_name, string $table_label = '', bool $is_tree = false): void
    {
        $this->buildClass('controller', $table_name, $table_label); //生成控制器类文件
        $this->buildClass('model', $table_name, $table_label, $is_tree); //生成模型类文件
        $this->buildClass('subscribe', $table_name, $table_label); //生成事件类文件
    }


    /**
     * 移除模型相关类文件
     * @param string $table_name 模型表名（不含前缀）
     * @return void
     * @throws Exception
     */
    public function removeModelClass(string $table_name): void
    {
        $this->removeClass('controller', $table_name);  //移除控制器类文件
        $this->removeClass('model', $table_name);  //移除模型类文件
        $this->removeClass('subscribe', $table_name);  //移除事件类文件
    }


    /**
     * 生成类文件
     * @param string $type          controller, model, subscribe
     * @param string $table_name    模型表名（不含前缀）
     * @param string $table_label   模型标签
     * @param bool $is_tree         是否为树形模型
     * @return void
     * @throws Exception
     */
    public function buildClass(string $type, string $table_name, string $table_label = '', bool $is_tree = false): void
    {
        if(empty($table_name)) throw new Exception('生成类文件的参数table_name缺失.');

        $stub = file_get_contents($this->getStub($type, $is_tree));

        $class_name = toHump($table_name);
        $type == 'subscribe' && $class_name .= 'Event';

        $content = str_replace(['{%className%}', '{%classDoc%}'],[
            $class_name, $table_label
        ], $stub);

        $class_path = $this->getClassPath($type);
        if(!is_dir($class_path)) mkdir($class_path, 0755, true);

        file_put_contents($class_path . $class_name . '.php', $content);
    }


    /**
     * 移除类文件
     * @param string $type controller, model, subscribe
     * @param string $table_name 模型表名（不含前缀）
     * @return void
     * @throws Exception
     */
    public function removeClass(string $type, string $table_name): void
    {
        if(empty($type)) throw new Exception('生成类文件的参数type缺失.');
        if(empty($table_name)) throw new Exception('生成类文件的参数table_name缺失.');

        $class_name = toHump($table_name);
        $type == 'subscribe' && $class_name .= 'Event';
        $class_path = $this->getClassPath($type);
        $full_class = $class_path . $class_name . '.php';

        if(file_exists($full_class)) unlink($full_class);

    }


    /**
     * 获取数据迁移实例
     * @return Migrator
     */
    public function getMigrator()
    {
        $migrator = new Migrator(1);

        $default = config('database.default');
        $config = config("database.connections.{$default}");

        if (0 == $config['deploy']) {
            $options = [
                'adapter'      => $config['type'],
                'host'         => $config['hostname'],
                'name'         => $config['database'],
                'user'         => $config['username'],
                'pass'         => $config['password'],
                'port'         => $config['hostport'],
                'charset'      => $config['charset'],
                'table_prefix' => $config['prefix'],
            ];
        } else {
            $options = [
                'adapter'      => explode(',', $config['type'])[0],
                'host'         => explode(',', $config['hostname'])[0],
                'name'         => explode(',', $config['database'])[0],
                'user'         => explode(',', $config['username'])[0],
                'pass'         => explode(',', $config['password'])[0],
                'port'         => explode(',', $config['hostport'])[0],
                'charset'      => explode(',', $config['charset'])[0],
                'table_prefix' => explode(',', $config['prefix'])[0],
            ];
        }

        $table = config('database.migration_table', 'migrations');

        $options['default_migration_table'] = $options['table_prefix'] . $table;

        $adapter = AdapterFactory::instance()->getAdapter($options['adapter'], $options);

        if ($adapter->hasOption('table_prefix') || $adapter->hasOption('table_suffix')) {
            $adapter = AdapterFactory::instance()->getWrapper('prefix', $adapter);
        }

        $migrator->setAdapter($adapter);
        return $migrator;
    }


    /**
     * 生成模型初始数据
     * @param int $model_id      模型ID
     * @param string $table_name 模型表名（不含前缀）
     * @param string $label      模型标签
     * @param int $is_tree       10=是， 20=否
     * @param string $remark     模型备注
     * @return void
     */
    public function buildModelData(int $model_id, string $table_name, string $label, int $is_tree, string $remark = ''): void
    {
        //创建表
        $migrator = $this->getMigrator();
        if($is_tree == 10){
            $migrator->table($table_name, ['id' => true,  'comment'=>$remark])
                ->addColumn('title', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '标题'])
                ->addColumn('pid', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '父级DI'])
                ->addColumn('id_path', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => 'ID层级路径'])
                ->addColumn('sort_num', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '排序(小在前)'])
                ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
                ->create();
            //添加字段信息
            $migrator->table('model_field')->insert([
                ['field_name' => 'id', 'label' => 'ID', 'model_id' => $model_id, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20,  'sort_num' => 0, 'status' => 10],
                ['field_name' => 'title', 'label' => '标题', 'model_id' => $model_id, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '标题', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 0, 'is_filter' => 10,  'sort_num' => 60, 'status' => 10],
                ['field_name' => 'pid', 'label' => '父级', 'model_id' => $model_id, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '父级ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10,  'sort_num' => 9996, 'status' => 10],
                ['field_name' => 'id_path', 'label' => '层级路径', 'model_id' => $model_id, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => 'ID层级路径', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10,  'sort_num' => 9997, 'status' => 10],
                ['field_name' => 'sort_num', 'label' => '排序', 'model_id' => $model_id, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '排列顺序(小在前)', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10,  'sort_num' => 9998, 'status' => 10],
                ['field_name' => 'status', 'label' => '状态', 'model_id' => $model_id, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10,  'sort_num' => 9999, 'status' => 10],
            ])->save();
        }else{
            $migrator->table($table_name, ['id' => true,  'comment'=>$remark])
                ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
                ->create();
            //添加字段信息
            $migrator->table('model_field')->insert([
                ['field_name' => 'id', 'label' => 'ID', 'model_id' => $model_id, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20,  'sort_num' => 0, 'status' => 10],
                ['field_name' => 'status', 'label' => '状态', 'model_id' => $model_id, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10,  'sort_num' => 9999, 'status' => 10],
            ])->save();
        }

        //表前缀
        $table_prefix = $migrator->getAdapter()->getOption('table_prefix');

        //添加字段选项
        $model_field = $migrator->fetchRow('select id from '.$table_prefix.'model_field where model_id = '.$model_id.' and status = 10 and field_name = "status"');

        $migrator->table('field_option')->insert([
            ['model_id' => $model_id, 'model_field_id' => $model_field['id'], 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => $model_id, 'model_field_id' => $model_field['id'], 'option_value' => 20, 'option_label' => '禁用'],
        ])->save();

        //添加动作信息
        $migrator->table('model_action')->insert([
            ['label' => $label.'管理列表', 'api_path' => '/vuecmf/'.$table_name, 'model_id' => $model_id, 'action_type' => 'list'],
            ['label' => '保存'.$label, 'api_path' => '/vuecmf/'.$table_name.'/save', 'model_id' => $model_id, 'action_type' => 'save'],
            ['label' => '删除'.$label, 'api_path' => '/vuecmf/'.$table_name.'/delete', 'model_id' => $model_id, 'action_type' => 'delete'],
            ['label' => $label.'下拉列表', 'api_path' => '/vuecmf/'.$table_name.'/dropdown', 'model_id' => $model_id, 'action_type' => 'dropdown'],
            ['label' => '批量保存'.$label, 'api_path' => '/vuecmf/'.$table_name.'/saveAll', 'model_id' => $model_id, 'action_type' => 'save_all'],
        ])->save();

        //设置模型的默认动作
        $model_action = $migrator->fetchRow('select id from '.$table_prefix.'model_action where model_id = '.$model_id.' and status = 10 and action_type = "list"');
        ModelConfig::where('id', $model_id)->update(['default_action_id' => $model_action['id']]);

    }


    /**
     * 移除模型相关数据
     * @param int $model_id 模型ID
     * @param string $table_name 模型表名（不含前缀）
     * @return void
     * @throws \think\db\exception\DbException
     */
    public function removeModelData(int $model_id, string $table_name): void
    {
        //根据动作表找到对应权限项，清除rules表相关信息
        $action_list = ModelAction::where('model_id',  $model_id)->where('status', 10)->column('api_path');
        if(!empty($action_list)){
            foreach ($action_list as $path){
                $arr = explode('/', trim($path,'/'));
                if(count($arr) < 2) continue;
                $app_name = $arr[0];
                $controller = $arr[1];
                $action = $arr[2] ?? 'index';

                \think\facade\Db::name('rules')
                    ->where('v1', $app_name)
                    ->where('v2', $controller)
                    ->where('v3', $action)
                    ->delete();
            }
        }

        //清除字段信息
        ModelField::where('model_id', $model_id)->delete();

        //清除索引信息
        ModelIndex::where('model_id', $model_id)->delete();

        //清除字段选项
        FieldOption::where('model_id', $model_id)->delete();

        //清除关联表信息
        ModelRelation::where('model_id', $model_id)->delete();

        //清除动作信息
        ModelAction::where('model_id', $model_id)->delete();

        //清除表单信息
        ModelForm::where('model_id', $model_id)->delete();

        //清除表单校验规则信息
        ModelFormRules::where('model_id', $model_id)->delete();

        //清除表单联动信息
        ModelFormLinkage::where('model_id', $model_id)->delete();

        //清除菜单信息
        Menu::where('model_id', $model_id)->delete();

        //删除模型对应表及相关数据
        $migrator = $this->getMigrator();
        $migrator->table($table_name)->drop();

    }


    /**
     * 更新表名
     * @param string $old_table_name 原表名（不含前缀）
     * @param string $new_table_name 新表名（不含前缀）
     * @return void
     */
    public function renameTable(string $old_table_name, string $new_table_name)
    {
        $migrator = $this->getMigrator();
        $migrator->table($old_table_name)->rename($new_table_name);
    }


    /**
     * 添加表字段
     * @param string $table_name 表名（不含前缀）
     * @param string $field_name 字段名
     * @param string|null $type 字段类型： string, text, integer, float, decimal, datetime, timestamp, time, date, binary, boolean
     * @param array $options 字段选项：
     *       [
                * 'limit' => 255,  //字符长度限制
                * 'default' => '', //字段默认值
                * 'null' => true,  //字段是否允许空值
                * 'identity' => false, //字段值是否唯一
                * 'precision' => 12, //字段为decimal类型时的精度
                * 'scale' => 5,  //字段为decimal类型时的精度
                * 'after' => 'id', //设置要在其后添加此列的列的名称
                * 'update' => '', //设置'ON UPDATE' mysql 列函数
                * 'comment' => '标题', //字段的备注信息
                * 'signed' => false, //设置字段是否可为负数
                * 'timezone' => false, //设置字段是否应具有时区标识符
                * 'properties' => [], //设置字段属性
                * 'values' => '', //设置字段值
     *       ]
     * @return void
     */
    public function addField(string $table_name, string $field_name, string $type = null, array $options = [])
    {
        $migrator = $this->getMigrator();
        $migrator->table($table_name)->addColumn($field_name, $type, $options)->update();
    }

    /**
     * 重命名字段名
     * @param string $table_name 表名（不含前缀）
     * @param string $old_field_name 原字段名
     * @param string $new_field_name 新字段名
     * @return void
     */
    public function renameField(string $table_name, string $old_field_name, string $new_field_name)
    {
        $migrator = $this->getMigrator();
        $migrator->table($table_name)->renameColumn($old_field_name, $new_field_name)->update();
    }

    /**
     * 删除字段
     * @param string $table_name 表名（不含前缀）
     * @param string $field_name 字段名
     * @return void
     */
    public function delField(string $table_name, string $field_name)
    {
        $migrator = $this->getMigrator();
        $migrator->table($table_name)->removeColumn($field_name)->update();
    }

    /**
     * 添加索引
     * @param string $table_name 表名（不含前缀）
     * @param array $field_name_list 需要添加索引的字段名列表
     * @param string $type 索引类型  NORMAL 、 UNIQUE 、 FULLTEXT
     * @return void
     */
    public function addIndex(string $table_name, array $field_name_list, string $type = 'NORMAL')
    {
        $migrator = $this->getMigrator();
        $type_maps = [
            'NORMAL' => 'index',
            'UNIQUE' => 'unique',
            'FULLTEXT' => 'fulltext'
        ];
        $options = [
            //'unique' => false,  //是否为唯一索引
            'type' => $type_maps[$type],  //索引类型 unique、index、fulltext
            'name' => 'idx_'. implode('_', $field_name_list), //索引名称
            //'limit' => 18, //索引长度
        ];

        $migrator->table($table_name)->addIndex($field_name_list, $options)->update();

    }

    /**
     * 删除索引
     * @param string $table_name 表名（不含前缀）
     * @param array $field_name_list 需要添加索引的字段名列表
     * @param string $type 索引类型: NORMAL 、 UNIQUE 、 FULLTEXT
     * @return void
     */
    public function delIndex(string $table_name, array $field_name_list, string $type = 'NORMAL')
    {
        $migrator = $this->getMigrator();

        $type_maps = [
            'NORMAL' => 'index',
            'UNIQUE' => 'unique',
            'FULLTEXT' => 'fulltext'
        ];
        $options = [
            'type' => $type_maps[$type],  //索引类型 unique、index、fulltext
            'name' => 'idx_'. implode('_', $field_name_list), //索引名称
        ];
        $migrator->table($table_name)->removeIndex($field_name_list, $options)->update();
    }


    /**
     * 获取模板内容
     * @param string $type
     * @return string
     */
    protected function getStub(string $type, $is_tree = false): string
    {
        $app_path = app_path();
        $stub_name = $type == 'model' && $is_tree ? 'tree_' . $type : $type;
        return $app_path . 'make' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $stub_name . '.stub';
    }

    /**
     * 获取生成类的目录
     * @param string $type
     * @return string
     */
    protected function getClassPath(string $type): string
    {
        $app_path = app_path();
        return $app_path . $type . DIRECTORY_SEPARATOR;
    }


}