<?php

use think\migration\Migrator;
use think\migration\db\Column;

class VuecmfDatabase extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     * biginteger
    binary
    boolean
    date
    datetime
    decimal
    float
    integer
    string
    text
    time
    timestamp
    uuid
     */
    public function up()
    {
        $this->table('model', ['id' => true,  'comment'=>'系统--模型管理表'])
            ->addColumn('table_name', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '模型对应的表名(不含表前缘)'])
            ->addColumn('label', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '模型标签'])
            ->addColumn('default_action_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '默认动作ID'])
            ->addColumn('component_tpl', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '组件模板'])
            ->addColumn('search_field_id', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '搜索字段ID，多个用竖线分隔'])
            ->addColumn('type', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '类型：10=内置，20=扩展'])
            ->addColumn('is_tree', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '是否为目录树：10=是，20=否'])
            ->addColumn('remark', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '模型对应表的备注'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['table_name'], ['unique' => true])
            ->create();

        $this->table('model_field', ['id' => true,  'comment'=>'系统--模型字段管理表'])
            ->addColumn('field_name', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '字段名称'])
            ->addColumn('label', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '字段中文名称'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '所属模型ID'])
            ->addColumn('type', 'string', ['length' => 20, 'null' => false, 'default' => '', 'comment' => '字段类型'])
            ->addColumn('length', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '字段长度'])
            ->addColumn('decimal_length', 'integer', ['length' => 2, 'null' => false, 'default' => 0, 'comment' => '小数位数长度'])
            ->addColumn('is_null', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '是否为空：10=是，20=否'])
            ->addColumn('note', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '字段备注说明'])
            ->addColumn('default_value', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '表单默认值'])
            ->addColumn('is_auto_increment', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '是否自动递增：10=是，20=否'])
            ->addColumn('is_label', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '是否为标题字段：10=是，20=否'])
            ->addColumn('is_signed', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '是否可为负数：10=是，20=否'])
            ->addColumn('is_show', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '默认列表中显示：10=显示，20=不显示'])
            ->addColumn('is_fixed', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '默认列表中固定：10=固定，20=不固定'])
            ->addColumn('column_width', 'integer', ['length' => 11, 'null' => false, 'default' => 150, 'comment' => '默认列宽度'])
            ->addColumn('is_filter', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '是否可筛选：10=是，20=否'])
            ->addColumn('is_disabled', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '表单是否禁用： 10=是，20=否'])
            ->addColumn('sort_num', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '排序(小在前)'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['field_name','model_id'], ['unique' => true])
            ->create();

        $this->table('field_option', ['id' => true,  'comment'=>'系统--字段的选项列表'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '所属模型ID'])
            ->addColumn('model_field_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型字段ID'])
            ->addColumn('option_value', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '选项值'])
            ->addColumn('option_label', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '选项标签'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['model_field_id','option_value'], ['unique' => true])
            ->create();

        $this->table('model_action', ['id' => true,  'comment'=>'系统--模型动作表'])
            ->addColumn('action_name', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '动作名称（vue的路由链接）'])
            ->addColumn('label', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '动作标签'])
            ->addColumn('api_path', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '后端请求地址'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '所属模型ID'])
            ->addColumn('action_type', 'string', ['length' => 32, 'null' => false, 'default' => '', 'comment' => '动作类型'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['action_name','model_id'], ['unique' => true])
            ->create();

        $this->table('model_relation', ['id' => true,  'comment'=>'系统--模型关联设置表'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型ID'])
            ->addColumn('model_field_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型字段ID'])
            ->addColumn('relation_model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '关联模型ID'])
            ->addColumn('relation_field_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '关联模型字段ID'])
            ->addColumn('relation_condition', 'string', ['length' => 6, 'null' => false, 'default' => '=', 'comment' => '关联条件'])
            ->addColumn('condition_type', 'string', ['length' => 10, 'null' => false, 'default' => 'and', 'comment' => '条件类型'])
            ->addColumn('join_type', 'string', ['length' => 24, 'null' => false, 'default' => 'left', 'comment' => '连接类型'])
            ->addColumn('relation_show_field_id', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '关联模型显示字段ID,多个逗号分隔，全部用*'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['model_field_id','relation_field_id'], ['unique' => true])
            ->create();

        $this->table('model_index', ['id' => true,  'comment'=>'系统--模型索引设置表'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型ID'])
            ->addColumn('model_field_id', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '模型字段ID, 多个用逗号分隔'])
            ->addColumn('index_type', 'string', ['length' => 32, 'null' => false, 'default' => 'NORMAL', 'comment' => '索引类型： PRIMARY=主键，NORMAL=常规，UNIQUE=唯一，FULLTEXT=全文'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->create();

        $this->table('menu', ['id' => true,  'comment'=>'系统--菜单表'])
            ->addColumn('title', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '菜单标题'])
            ->addColumn('icon', 'string', ['length' => 32, 'null' => false, 'default' => '', 'comment' => '菜单图标'])
            ->addColumn('pid', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '父级DI'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型ID'])
            ->addColumn('sort_num', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '排序(小在前)'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->create();

        $this->table('admin', ['id' => true,  'comment'=>'系统--管理员表'])
            ->addColumn('username', 'string', ['length' => 16, 'null' => false, 'default' => '', 'comment' => '用户名'])
            ->addColumn('password', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '密码'])
            ->addColumn('email', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '邮箱'])
            ->addColumn('mobile', 'string', ['length' => 32, 'null' => false, 'default' => '', 'comment' => '手机'])
            ->addColumn('is_super', 'integer', ['length' => 4, 'null' => false, 'default' => 20, 'comment' => '超级管理员：10=是，20=否'])
            ->addColumn('reg_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '注册时间'])
            ->addColumn('reg_ip', 'biginteger', ['length' => 20, 'null' => false, 'default' => 0, 'comment' => '注册IP'])
            ->addColumn('last_login_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '最后登录时间'])
            ->addColumn('last_login_ip', 'biginteger', ['length' => 20, 'null' => false, 'default' => 0, 'comment' => '最后登录IP'])
            ->addColumn('update_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
            ->addColumn('token', 'string', ['null' => false, 'default' => '', 'comment' => 'api访问token'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['username'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['mobile'], ['unique' => true])
            ->create();

        //写入初始数据
        $data = [
            // model 模型
            ['id' => 1, 'table_name' => 'model', 'label' => '模型列表', 'default_action_id' => 1, 'component_tpl' => 'template/list/single_list', 'search_field_id' => '2,3,5', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--模型管理表', 'status' => 10],
            // model_action 模型
            ['id' => 2, 'table_name' => 'model_action', 'label' => '模型动作', 'default_action_id' => 4, 'component_tpl' => 'template/list/category_list', 'search_field_id' => '12,13,14,16', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--模型动作表', 'status' => 10],
            // model_field 模型
            ['id' => 3, 'table_name' => 'model_field', 'label' => '模型字段', 'default_action_id' => 8, 'component_tpl' => 'template/list/category_list', 'search_field_id' => '19,20,22', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--模型字段管理表', 'status' => 10],
            // field_option 模型
            ['id' => 4, 'table_name' => 'field_option', 'label' => '字段选项', 'default_action_id' => 12, 'component_tpl' => 'template/list/category_list', 'search_field_id' => '41,42', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--字段的选项列表', 'status' => 10],
            // model_index 模型
            ['id' => 5, 'table_name' => 'model_index', 'label' => '模型索引', 'default_action_id' => 17, 'component_tpl' => 'template/list/category_list', 'search_field_id' => '', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--模型索引设置表', 'status' => 10],
            // model_relation 模型
            ['id' => 6, 'table_name' => 'model_relation', 'label' => '模型关联', 'default_action_id' => 22, 'component_tpl' => 'template/list/category_list', 'search_field_id' => '54,55,56', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--模型关联设置表', 'status' => 10],
            // menu 模型
            ['id' => 7, 'table_name' => 'menu', 'label' => '菜单', 'default_action_id' => 27, 'component_tpl' => 'template/list/menu_tree', 'search_field_id' => '60,61', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--菜单表', 'status' => 10],
            // admin 模型
            ['id' => 8, 'table_name' => 'admin', 'label' => '管理员', 'default_action_id' => , 'component_tpl' => 'template/list/admin_list', 'search_field_id' => '', 'type' => 10, 'is_tree' => 20, 'remark' => '系统--管理员表', 'status' => 10],

        ];
        $this->table('model')->insert($data)->save();

        $data = [
            //模型表字段
            ['id' => 1, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 1, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 1, 'status' => 10],
            ['id' => 2, 'field_name' => 'table_name', 'label' => '表名', 'model_id' => 1, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型对应的表名(不含表前缘)', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 2, 'status' => 10],
            ['id' => 3, 'field_name' => 'label', 'label' => '模型标签', 'model_id' => 1, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型标签', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 3, 'status' => 10],
            ['id' => 4, 'field_name' => 'default_action_id', 'label' => '默认动作', 'model_id' => 1, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '默认动作ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 4, 'status' => 10],
            ['id' => 5, 'field_name' => 'component_tpl', 'label' => '组件模板', 'model_id' => 1, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '组件模板', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 5, 'status' => 10],
            ['id' => 6, 'field_name' => 'search_field_id', 'label' => '搜索字段', 'model_id' => 1, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '搜索字段ID，多个用逗号分隔', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 20, 'is_disabled' => 20, 'sort_num' => 6, 'status' => 10],
            ['id' => 7, 'field_name' => 'type', 'label' => '类型', 'model_id' => 1, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '类型：10=内置，20=扩展', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 7, 'status' => 10],
            ['id' => 8, 'field_name' => 'is_tree', 'label' => '目录树', 'model_id' => 1, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '是否为目录树：10=是，20=否', 'default_value' => 20, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 8, 'status' => 10],
            ['id' => 9, 'field_name' => 'remark', 'label' => '表备注', 'model_id' => 1, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型对应表的备注', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 9, 'status' => 10],
            ['id' => 10, 'field_name' => 'status', 'label' => '状态', 'model_id' => 1, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 10, 'status' => 10],
            //表 model_action 字段
            ['id' => 11, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 2, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 11, 'status' => 10],
            ['id' => 12, 'field_name' => 'action_name', 'label' => '动作名称', 'model_id' => 2, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '动作名称（vue的路由链接）', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 12, 'status' => 10],
            ['id' => 13, 'field_name' => 'label', 'label' => '动作标签', 'model_id' => 2, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '动作标签', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 13, 'status' => 10],
            ['id' => 14, 'field_name' => 'api_path', 'label' => '后端请求地址', 'model_id' => 2, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '后端请求地址', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 14, 'status' => 10],
            ['id' => 15, 'field_name' => 'model_id', 'label' => '所属模型', 'model_id' => 2, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '所属模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 15, 'status' => 10],
            ['id' => 16, 'field_name' => 'action_type', 'label' => '动作类型', 'model_id' => 2, 'type' => 'varchar', 'length' => 32, 'decimal_length' => 0, 'is_null' => 20, 'note' => '动作类型', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 16, 'status' => 10],
            ['id' => 17, 'field_name' => 'status', 'label' => '状态', 'model_id' => 2, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 17, 'status' => 10],
            //表 model_field 字段
            ['id' => 18, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 3, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 18, 'status' => 10],
            ['id' => 19, 'field_name' => 'field_name', 'label' => '字段名称', 'model_id' => 3, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表的字段名称', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 19, 'status' => 10],
            ['id' => 20, 'field_name' => 'label', 'label' => '字段中文名', 'model_id' => 3, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表的字段中文名称', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 20, 'status' => 10],
            ['id' => 21, 'field_name' => 'model_id', 'label' => '所属模型', 'model_id' => 3, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '所属模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 21, 'status' => 10],
            ['id' => 22, 'field_name' => 'type', 'label' => '字段类型', 'model_id' => 3, 'type' => 'varchar', 'length' => 20, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表的字段类型', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 22, 'status' => 10],
            ['id' => 23, 'field_name' => 'length', 'label' => '字段长度', 'model_id' => 3, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表的字段长度', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 23, 'status' => 10],
            ['id' => 24, 'field_name' => 'decimal_length', 'label' => '小数位数', 'model_id' => 3, 'type' => 'int', 'length' => 2, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表的字段为decimal类型时的小数位数', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 24, 'status' => 10],
            ['id' => 25, 'field_name' => 'is_null', 'label' => '是否为空', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '是否为空：10=是，20=否', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 25, 'status' => 10],
            ['id' => 26, 'field_name' => 'note', 'label' => '字段备注', 'model_id' => 3, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表的字段备注说明', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 26, 'status' => 10],
            ['id' => 27, 'field_name' => 'default_value', 'label' => '默认值', 'model_id' => 3, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表单默认值', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 27, 'status' => 10],
            ['id' => 28, 'field_name' => 'is_auto_increment', 'label' => '自动递增', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '是否自动递增：10=是，20=否', 'default_value' => 20, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 28, 'status' => 10],
            ['id' => 29, 'field_name' => 'is_label', 'label' => '标题字段', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '是否为标题字段：10=是，20=否', 'default_value' => 20, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 29, 'status' => 10],
            ['id' => 30, 'field_name' => 'is_signed', 'label' => '可为负数', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '是否可为负数：10=是，20=否', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 30, 'status' => 10],
            ['id' => 31, 'field_name' => 'is_show', 'label' => '列表可显', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '默认列表中显示：10=显示，20=不显示', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 31, 'status' => 10],
            ['id' => 32, 'field_name' => 'is_fixed', 'label' => '固定列', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '默认列表中固定：10=固定，20=不固定', 'default_value' => 20, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 32, 'status' => 10],
            ['id' => 33, 'field_name' => 'column_width', 'label' => '列宽度', 'model_id' => 3, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '列表中默认显示宽度：0表示不限', 'default_value' => 150, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 33, 'status' => 10],
            ['id' => 34, 'field_name' => 'is_filter', 'label' => '可筛选', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '列表中是否可为筛选条件：10=是，20=否', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 34, 'status' => 10],
            ['id' => 35, 'field_name' => 'is_disabled', 'label' => '是否禁用', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '添加/编辑表单是否禁用： 10=是，20=否', 'default_value' => 20, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 35, 'status' => 10],
            ['id' => 36, 'field_name' => 'sort_num', 'label' => '排序', 'model_id' => 3, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '表单/列表中字段的排列顺序(小在前)', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 36, 'status' => 10],
            ['id' => 37, 'field_name' => 'status', 'label' => '状态', 'model_id' => 3, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 37, 'status' => 10],
            //表 field_option 字段
            ['id' => 38, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 4, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 38, 'status' => 10],
            ['id' => 39, 'field_name' => 'model_id', 'label' => '所属模型', 'model_id' => 4, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '所属模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 39, 'status' => 10],
            ['id' => 40, 'field_name' => 'model_field_id', 'label' => '模型字段', 'model_id' => 4, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型字段ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 40, 'status' => 10],
            ['id' => 41, 'field_name' => 'option_value', 'label' => '选项值', 'model_id' => 4, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '选项值', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 41, 'status' => 10],
            ['id' => 42, 'field_name' => 'option_label', 'label' => '选项标签', 'model_id' => 4, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '选项标签', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 42, 'status' => 10],
            ['id' => 43, 'field_name' => 'status', 'label' => '状态', 'model_id' => 4, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 43, 'status' => 10],
            //表 model_index 字段
            ['id' => 44, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 5, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 44, 'status' => 10],
            ['id' => 45, 'field_name' => 'model_id', 'label' => '所属模型', 'model_id' => 5, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '所属模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 45, 'status' => 10],
            ['id' => 46, 'field_name' => 'model_field_id', 'label' => '模型字段', 'model_id' => 5, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型字段ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 46, 'status' => 10],
            ['id' => 47, 'field_name' => 'index_type', 'label' => '索引类型', 'model_id' => 5, 'type' => 'varchar', 'length' => 32, 'decimal_length' => 0, 'is_null' => 20, 'note' => '索引类型： PRIMARY=主键，NORMAL=常规，UNIQUE=唯一，FULLTEXT=全文', 'default_value' => 'NORMAL', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 47, 'status' => 10],
            ['id' => 48, 'field_name' => 'status', 'label' => '状态', 'model_id' => 5, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 48, 'status' => 10],
            //表 model_relation 字段
            ['id' => 49, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 6, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 49, 'status' => 10],
            ['id' => 50, 'field_name' => 'model_id', 'label' => '所属模型', 'model_id' => 6, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '所属模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 50, 'status' => 10],
            ['id' => 51, 'field_name' => 'model_field_id', 'label' => '模型字段', 'model_id' => 6, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型字段ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 51, 'status' => 10],
            ['id' => 52, 'field_name' => 'relation_model_id', 'label' => '关联模型', 'model_id' => 6, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '关联模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 52, 'status' => 10],
            ['id' => 53, 'field_name' => 'relation_field_id', 'label' => '关联模型字段', 'model_id' => 6, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '关联模型字段ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 53, 'status' => 10],
            ['id' => 54, 'field_name' => 'relation_condition', 'label' => '关联条件', 'model_id' => 6, 'type' => 'varchar', 'length' => 6, 'decimal_length' => 0, 'is_null' => 20, 'note' => '关联条件', 'default_value' => '=', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 54, 'status' => 10],
            ['id' => 55, 'field_name' => 'condition_type', 'label' => '条件类型', 'model_id' => 6, 'type' => 'varchar', 'length' => 10, 'decimal_length' => 0, 'is_null' => 20, 'note' => '条件类型', 'default_value' => 'and', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 55, 'status' => 10],
            ['id' => 56, 'field_name' => 'join_type', 'label' => '连接类型', 'model_id' => 6, 'type' => 'varchar', 'length' => 24, 'decimal_length' => 0, 'is_null' => 20, 'note' => '连接类型', 'default_value' => 'left', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 56, 'status' => 10],
            ['id' => 57, 'field_name' => 'relation_show_field_id', 'label' => '显示字段', 'model_id' => 6, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '关联模型显示字段ID,多个逗号分隔，全部用*', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 57, 'status' => 10],
            ['id' => 58, 'field_name' => 'status', 'label' => '状态', 'model_id' => 6, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 58, 'status' => 10],
            //表 menu 字段
            ['id' => 59, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 7, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 59, 'status' => 10],
            ['id' => 60, 'field_name' => 'title', 'label' => '菜单标题', 'model_id' => 7, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '菜单标题', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 60, 'status' => 10],
            ['id' => 61, 'field_name' => 'icon', 'label' => '菜单图标', 'model_id' => 7, 'type' => 'varchar', 'length' => 32, 'decimal_length' => 0, 'is_null' => 20, 'note' => '菜单图标', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 61, 'status' => 10],
            ['id' => 62, 'field_name' => 'pid', 'label' => '父级', 'model_id' => 7, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '父级ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 62, 'status' => 10],
            ['id' => 63, 'field_name' => 'model_id', 'label' => '模型', 'model_id' => 7, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '模型ID', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 63, 'status' => 10],
            ['id' => 64, 'field_name' => 'sort_num', 'label' => '排序', 'model_id' => 7, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '菜单的排列顺序(小在前)', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 64, 'status' => 10],
            ['id' => 65, 'field_name' => 'status', 'label' => '状态', 'model_id' => 7, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 65, 'status' => 10],
            //表 admin 字段
            ['id' => 66, 'field_name' => 'id', 'label' => 'ID', 'model_id' => 8, 'type' => 'int', 'length' => 11, 'decimal_length' => 0, 'is_null' => 20, 'note' => '自增ID', 'default_value' => 0, 'is_auto_increment' => 10, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 20, 'is_disabled' => 10, 'sort_num' => 66, 'status' => 10],
            ['id' => 67, 'field_name' => 'username', 'label' => '用户名', 'model_id' => 8, 'type' => 'varchar', 'length' => 16, 'decimal_length' => 0, 'is_null' => 20, 'note' => '用户名', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 10, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 67, 'status' => 10],
            ['id' => 68, 'field_name' => 'password', 'label' => '密码', 'model_id' => 8, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => '密码', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 20, 'is_fixed' => 20, 'column_width' => 0, 'is_filter' => 20, 'is_disabled' => 20, 'sort_num' => 68, 'status' => 10],
            ['id' => 69, 'field_name' => 'email', 'label' => '邮箱', 'model_id' => 8, 'type' => 'varchar', 'length' => 64, 'decimal_length' => 0, 'is_null' => 20, 'note' => '邮箱', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 69, 'status' => 10],
            ['id' => 70, 'field_name' => 'mobile', 'label' => '手机', 'model_id' => 8, 'type' => 'varchar', 'length' => 32, 'decimal_length' => 0, 'is_null' => 20, 'note' => '手机', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 70, 'status' => 10],
            ['id' => 71, 'field_name' => 'is_super', 'label' => '超级管理员', 'model_id' => 8, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '超级管理员：10=是，20=否', 'default_value' => 20, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 71, 'status' => 10],
            ['id' => 72, 'field_name' => 'reg_time', 'label' => '注册时间', 'model_id' => 8, 'type' => 'timestamp', 'length' => 0, 'decimal_length' => 0, 'is_null' => 20, 'note' => '注册时间', 'default_value' => 'CURRENT_TIMESTAMP', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 72, 'status' => 10],
            ['id' => 73, 'field_name' => 'reg_ip', 'label' => '注册IP', 'model_id' => 8, 'type' => 'bigint', 'length' => 20, 'decimal_length' => 0, 'is_null' => 20, 'note' => '注册IP', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 20, 'is_disabled' => 20, 'sort_num' => 73, 'status' => 10],
            ['id' => 74, 'field_name' => 'last_login_time', 'label' => '最后登录时间', 'model_id' => 8, 'type' => 'timestamp', 'length' => 0, 'decimal_length' => 0, 'is_null' => 20, 'note' => '最后登录时间', 'default_value' => 'CURRENT_TIMESTAMP', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 74, 'status' => 10],
            ['id' => 75, 'field_name' => 'last_login_ip', 'label' => '最后登录IP', 'model_id' => 8, 'type' => 'bigint', 'length' => 20, 'decimal_length' => 0, 'is_null' => 20, 'note' => '最后登录IP', 'default_value' => 0, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 20, 'is_disabled' => 20, 'sort_num' => 75, 'status' => 10],
            ['id' => 76, 'field_name' => 'update_time', 'label' => '更新时间', 'model_id' => 8, 'type' => 'timestamp', 'length' => 0, 'decimal_length' => 0, 'is_null' => 20, 'note' => '更新时间', 'default_value' => 'CURRENT_TIMESTAMP', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 76, 'status' => 10],
            ['id' => 77, 'field_name' => 'token', 'label' => '访问token', 'model_id' => 8, 'type' => 'varchar', 'length' => 255, 'decimal_length' => 0, 'is_null' => 20, 'note' => 'api访问token', 'default_value' => '', 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 150, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 77, 'status' => 10],
            ['id' => 78, 'field_name' => 'status', 'label' => '状态', 'model_id' => 8, 'type' => 'int', 'length' => 4, 'decimal_length' => 0, 'is_null' => 20, 'note' => '状态：10=开启，20=禁用', 'default_value' => 10, 'is_auto_increment' => 20, 'is_label' => 20, 'is_signed' => 20, 'is_show' => 10, 'is_fixed' => 20, 'column_width' => 100, 'is_filter' => 10, 'is_disabled' => 20, 'sort_num' => 78, 'status' => 10],


        ];
        $this->table('model_field')->insert($data)->save();

        $data = [
            //表model 索引
            ['model_id' => 1, 'model_field_id' => '2', 'index_type' => 'UNIQUE'],
            //表 model_action 索引
            ['model_id' => 2, 'model_field_id' => '12,15', 'index_type' => 'UNIQUE'],
            //表 model_field 索引
            ['model_id' => 3, 'model_field_id' => '19,21', 'index_type' => 'UNIQUE'],
            //表 field_option 索引
            ['model_id' => 4, 'model_field_id' => '40,41', 'index_type' => 'UNIQUE'],
            //表 model_relation 索引
            ['model_id' => 6, 'model_field_id' => '51,53', 'index_type' => 'UNIQUE'],
            //表 admin 索引
            ['model_id' => 8, 'model_field_id' => '67', 'index_type' => 'UNIQUE'],
            ['model_id' => 8, 'model_field_id' => '69', 'index_type' => 'UNIQUE'],
            ['model_id' => 8, 'model_field_id' => '70', 'index_type' => 'UNIQUE'],
        ];
        $this->table('model_index')->insert($data)->save();

        $data = [
            ['model_id' => 1, 'model_field_id' => 7, 'option_value' => 10, 'option_label' => '内置'],
            ['model_id' => 1, 'model_field_id' => 7, 'option_value' => 20, 'option_label' => '扩展'],
            ['model_id' => 1, 'model_field_id' => 8, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 1, 'model_field_id' => 8, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 1, 'model_field_id' => 10, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 1, 'model_field_id' => 10, 'option_value' => 20, 'option_label' => '禁用'],
            ['model_id' => 2, 'model_field_id' => 17, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 2, 'model_field_id' => 17, 'option_value' => 20, 'option_label' => '禁用'],
            ['model_id' => 3, 'model_field_id' => 25, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 3, 'model_field_id' => 25, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 3, 'model_field_id' => 28, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 3, 'model_field_id' => 28, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 3, 'model_field_id' => 29, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 3, 'model_field_id' => 29, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 3, 'model_field_id' => 30, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 3, 'model_field_id' => 30, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 3, 'model_field_id' => 31, 'option_value' => 10, 'option_label' => '显示'],
            ['model_id' => 3, 'model_field_id' => 31, 'option_value' => 20, 'option_label' => '不显示'],
            ['model_id' => 3, 'model_field_id' => 32, 'option_value' => 10, 'option_label' => '固定'],
            ['model_id' => 3, 'model_field_id' => 32, 'option_value' => 20, 'option_label' => '不固定'],
            ['model_id' => 3, 'model_field_id' => 34, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 3, 'model_field_id' => 34, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 3, 'model_field_id' => 35, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 3, 'model_field_id' => 35, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 3, 'model_field_id' => 37, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 3, 'model_field_id' => 37, 'option_value' => 20, 'option_label' => '禁用'],
            ['model_id' => 4, 'model_field_id' => 43, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 4, 'model_field_id' => 43, 'option_value' => 20, 'option_label' => '禁用'],
            ['model_id' => 5, 'model_field_id' => 47, 'option_value' => 'PRIMARY', 'option_label' => '主键'],
            ['model_id' => 5, 'model_field_id' => 47, 'option_value' => 'NORMAL', 'option_label' => '常规'],
            ['model_id' => 5, 'model_field_id' => 47, 'option_value' => 'UNIQUE', 'option_label' => '唯一'],
            ['model_id' => 5, 'model_field_id' => 47, 'option_value' => 'FULLTEXT', 'option_label' => '全文'],
            ['model_id' => 6, 'model_field_id' => 54, 'option_value' => '=', 'option_label' => '等于'],
            ['model_id' => 6, 'model_field_id' => 54, 'option_value' => '>', 'option_label' => '大于'],
            ['model_id' => 6, 'model_field_id' => 54, 'option_value' => '<', 'option_label' => '小于'],
            ['model_id' => 6, 'model_field_id' => 54, 'option_value' => '>=', 'option_label' => '大于等于'],
            ['model_id' => 6, 'model_field_id' => 54, 'option_value' => '<=', 'option_label' => '小于等于'],
            ['model_id' => 6, 'model_field_id' => 55, 'option_value' => 'and', 'option_label' => '且'],
            ['model_id' => 6, 'model_field_id' => 55, 'option_value' => 'or', 'option_label' => '或'],
            ['model_id' => 6, 'model_field_id' => 56, 'option_value' => 'left', 'option_label' => '左连接'],
            ['model_id' => 6, 'model_field_id' => 56, 'option_value' => 'right', 'option_label' => '右连接'],
            ['model_id' => 6, 'model_field_id' => 56, 'option_value' => 'inner', 'option_label' => '内连接'],
            ['model_id' => 6, 'model_field_id' => 58, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 6, 'model_field_id' => 58, 'option_value' => 20, 'option_label' => '禁用'],
            ['model_id' => 7, 'model_field_id' => 65, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 7, 'model_field_id' => 65, 'option_value' => 20, 'option_label' => '禁用'],
            ['model_id' => 8, 'model_field_id' => 71, 'option_value' => 10, 'option_label' => '是'],
            ['model_id' => 8, 'model_field_id' => 71, 'option_value' => 20, 'option_label' => '否'],
            ['model_id' => 8, 'model_field_id' => 78, 'option_value' => 10, 'option_label' => '开启'],
            ['model_id' => 8, 'model_field_id' => 78, 'option_value' => 20, 'option_label' => '禁用'],

        ];
        $this->table('field_option')->insert($data)->save();

        $data = [
            ['model_id' => 1, 'model_field_id' => 4, 'relation_model_id' => 2, 'relation_field_id' => 11, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '12,13'],
            ['model_id' => 2, 'model_field_id' => 15, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],
            ['model_id' => 3, 'model_field_id' => 21, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],
            ['model_id' => 4, 'model_field_id' => 39, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],
            ['model_id' => 4, 'model_field_id' => 40, 'relation_model_id' => 3, 'relation_field_id' => 18, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '19,20'],
            ['model_id' => 5, 'model_field_id' => 45, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],
            ['model_id' => 5, 'model_field_id' => 46, 'relation_model_id' => 3, 'relation_field_id' => 18, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '19,20'],
            ['model_id' => 6, 'model_field_id' => 50, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],
            ['model_id' => 6, 'model_field_id' => 51, 'relation_model_id' => 3, 'relation_field_id' => 18, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '19,20'],
            ['model_id' => 6, 'model_field_id' => 52, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],
            ['model_id' => 6, 'model_field_id' => 53, 'relation_model_id' => 3, 'relation_field_id' => 18, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '19,20'],
            ['model_id' => 7, 'model_field_id' => 63, 'relation_model_id' => 1, 'relation_field_id' => 1, 'relation_condition' => '=', 'condition_type' => 'and', 'join_type' => 'left', 'relation_show_field_id' => '2,3'],

        ];
        $this->table('model_relation')->insert($data)->save();

        $data = [
            ['id' => 1, 'action_name' => 'get_model_list', 'label' => '模型管理列表', 'api_path' => '/vuecmf/model', 'model_id' => 1, 'action_type' => 'list'],
            ['id' => 2, 'action_name' => 'save_model', 'label' => '保存模型', 'api_path' => '/vuecmf/model/save', 'model_id' => 1, 'action_type' => 'save'],
            ['id' => 3, 'action_name' => 'del_model', 'label' => '删除模型', 'api_path' => '/vuecmf/model/delete', 'model_id' => 1, 'action_type' => 'delete'],

            ['id' => 4, 'action_name' => 'get_model_action_list', 'label' => '模型动作管理列表', 'api_path' => '/vuecmf/model_action', 'model_id' => 2, 'action_type' => 'list'],
            ['id' => 5, 'action_name' => 'save_model_action', 'label' => '保存模型动作', 'api_path' => '/vuecmf/model_action/save', 'model_id' => 2, 'action_type' => 'save'],
            ['id' => 6, 'action_name' => 'del_model_action', 'label' => '删除模型动作', 'api_path' => '/vuecmf/model_action/delete', 'model_id' => 2, 'action_type' => 'delete'],
            ['id' => 7, 'action_name' => 'get_model_list', 'label' => '模型下拉列表', 'api_path' => '/vuecmf/model', 'model_id' => 2, 'action_type' => 'dropdown'],

            ['id' => 8, 'action_name' => 'get_model_field_list', 'label' => '模型字段管理列表', 'api_path' => '/vuecmf/model_field', 'model_id' => 3, 'action_type' => 'list'],
            ['id' => 9, 'action_name' => 'save_model_field', 'label' => '保存模型字段', 'api_path' => '/vuecmf/model_field/save', 'model_id' => 3, 'action_type' => 'save'],
            ['id' => 10, 'action_name' => 'del_model_field', 'label' => '删除模型字段', 'api_path' => '/vuecmf/model_field/delete', 'model_id' => 3, 'action_type' => 'delete'],
            ['id' => 11, 'action_name' => 'get_model_list', 'label' => '模型下拉列表', 'api_path' => '/vuecmf/model', 'model_id' => 3, 'action_type' => 'dropdown'],

            ['id' => 12, 'action_name' => 'get_field_option_list', 'label' => '字段选项管理列表', 'api_path' => '/vuecmf/field_option', 'model_id' => 4, 'action_type' => 'list'],
            ['id' => 13, 'action_name' => 'save_field_option', 'label' => '保存字段选项', 'api_path' => '/vuecmf/field_option/save', 'model_id' => 4, 'action_type' => 'save'],
            ['id' => 14, 'action_name' => 'del_field_option', 'label' => '删除字段选项', 'api_path' => '/vuecmf/field_option/delete', 'model_id' => 4, 'action_type' => 'delete'],
            ['id' => 15, 'action_name' => 'get_model_list', 'label' => '模型下拉列表', 'api_path' => '/vuecmf/model', 'model_id' => 4, 'action_type' => 'dropdown'],
            ['id' => 16, 'action_name' => 'get_model_field_list', 'label' => '模型字段下拉列表', 'api_path' => '/vuecmf/model_field', 'model_id' => 4, 'action_type' => 'dropdown'],

            ['id' => 17, 'action_name' => 'get_model_index_list', 'label' => '模型索引管理列表', 'api_path' => '/vuecmf/model_index', 'model_id' => 5, 'action_type' => 'list'],
            ['id' => 18, 'action_name' => 'save_model_index', 'label' => '保存模型索引', 'api_path' => '/vuecmf/model_index/save', 'model_id' => 5, 'action_type' => 'save'],
            ['id' => 19, 'action_name' => 'del_model_index', 'label' => '删除模型索引', 'api_path' => '/vuecmf/model_index/delete', 'model_id' => 5, 'action_type' => 'delete'],
            ['id' => 20, 'action_name' => 'get_model_list', 'label' => '模型下拉列表', 'api_path' => '/vuecmf/model', 'model_id' => 5, 'action_type' => 'dropdown'],
            ['id' => 21, 'action_name' => 'get_model_field_list', 'label' => '模型字段下拉列表', 'api_path' => '/vuecmf/model_field', 'model_id' => 5, 'action_type' => 'dropdown'],

            ['id' => 22, 'action_name' => 'get_model_relation_list', 'label' => '模型关联管理列表', 'api_path' => '/vuecmf/model_relation', 'model_id' => 6, 'action_type' => 'list'],
            ['id' => 23, 'action_name' => 'save_model_relation', 'label' => '保存模型关联', 'api_path' => '/vuecmf/model_relation/save', 'model_id' => 6, 'action_type' => 'save'],
            ['id' => 24, 'action_name' => 'del_model_relation', 'label' => '删除模型关联', 'api_path' => '/vuecmf/model_relation/delete', 'model_id' => 6, 'action_type' => 'delete'],
            ['id' => 25, 'action_name' => 'get_model_list', 'label' => '模型下拉列表', 'api_path' => '/vuecmf/model', 'model_id' => 6, 'action_type' => 'dropdown'],
            ['id' => 26, 'action_name' => 'get_model_field_list', 'label' => '模型字段下拉列表', 'api_path' => '/vuecmf/model_field', 'model_id' => 6, 'action_type' => 'dropdown'],

            ['id' => 27, 'action_name' => 'get_menu_list', 'label' => '菜单管理列表', 'api_path' => '/vuecmf/menu', 'model_id' => 7, 'action_type' => 'list'],
            ['id' => 28, 'action_name' => 'save_menu', 'label' => '保存菜单', 'api_path' => '/vuecmf/menu/save', 'model_id' => 7, 'action_type' => 'save'],
            ['id' => 29, 'action_name' => 'del_menu', 'label' => '删除菜单', 'api_path' => '/vuecmf/menu/delete', 'model_id' => 7, 'action_type' => 'delete'],
            ['id' => 30, 'action_name' => 'get_model_list', 'label' => '模型下拉列表', 'api_path' => '/vuecmf/model', 'model_id' => 7, 'action_type' => 'dropdown'],
            ['id' => 31, 'action_name' => 'get_menu_dropdown', 'label' => '菜单下拉列表', 'api_path' => '/vuecmf/menu/dropdown', 'model_id' => 7, 'action_type' => 'dropdown'],
            ['id' => 32, 'action_name' => 'get_menu_nav', 'label' => '导航菜单', 'api_path' => '/vuecmf/menu/nav', 'model_id' => 7, 'action_type' => 'nav'],

            ['id' => 33, 'action_name' => 'get_admin_list', 'label' => '管理员列表', 'api_path' => '/vuecmf/admin', 'model_id' => 8, 'action_type' => 'list'],
            ['id' => 34, 'action_name' => 'save_admin', 'label' => '保存管理员', 'api_path' => '/vuecmf/admin/save', 'model_id' => 8, 'action_type' => 'save'],
            ['id' => 35, 'action_name' => 'del_admin', 'label' => '删除管理员', 'api_path' => '/vuecmf/admin/delete', 'model_id' => 8, 'action_type' => 'delete'],
            ['id' => 36, 'action_name' => 'admin_detail', 'label' => '管理员详情', 'api_path' => '/vuecmf/admin/detail', 'model_id' => 8, 'action_type' => 'detail'],
            ['id' => 37, 'action_name' => 'get_admin_dropdown', 'label' => '管理员下拉列表', 'api_path' => '/vuecmf/admin/dropdown', 'model_id' => 8, 'action_type' => 'dropdown'],
            ['id' => 38, 'action_name' => 'set_admin_role', 'label' => '分配角色', 'api_path' => '/vuecmf/admin/setRole', 'model_id' => 8, 'action_type' => 'set_role'],
            ['id' => 39, 'action_name' => 'login', 'label' => '登录后台', 'api_path' => '/vuecmf/admin/login', 'model_id' => 8, 'action_type' => 'login'],
            ['id' => 40, 'action_name' => 'logout', 'label' => '退出系统', 'api_path' => '/vuecmf/admin/logout', 'model_id' => 8, 'action_type' => 'logout'],

        ];
        $this->table('model_action')->insert($data)->save();

        $data = [];
        $this->table('admin')->insert($data)->save();

        $data = [];
        $this->table('menu')->insert($data)->save();

    }

    public function down()
    {
        $this->table('model')->drop();
        $this->table('model_field')->drop();
        $this->table('field_option')->drop();
        $this->table('model_action')->drop();
        $this->table('model_relation')->drop();
        $this->table('model_index')->drop();
        $this->table('menu')->drop();
        $this->table('admin')->drop();
    }
}
