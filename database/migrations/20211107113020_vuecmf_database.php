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
            ->addColumn('type', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '类型：10=内置，20=扩展'])
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
            ->create();

        $this->table('field_option', ['id' => true,  'comment'=>'系统--字段的选项列表'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '所属模型ID'])
            ->addColumn('model_field_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型字段ID'])
            ->addColumn('option_value', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '选项值'])
            ->addColumn('option_label', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '选项标签'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->create();

        $this->table('model_action', ['id' => true,  'comment'=>'系统--模型动作表'])
            ->addColumn('action_name', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '动作名称（vue的路由链接）'])
            ->addColumn('label', 'string', ['length' => 64, 'null' => false, 'default' => '', 'comment' => '动作标签'])
            ->addColumn('api_path', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '后端请求地址'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '所属模型ID'])
            ->addColumn('action_type', 'string', ['length' => 32, 'null' => false, 'default' => '', 'comment' => '动作类型'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->addIndex(['action_name'], ['unique' => true])
            ->create();

        $this->table('model_relation', ['id' => true,  'comment'=>'系统--模型关联设置表'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型ID'])
            ->addColumn('model_field_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型字段ID'])
            ->addColumn('relation_model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '关联模型ID'])
            ->addColumn('relation_model_field_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '关联模型字段ID'])
            ->addColumn('relation_condition', 'string', ['length' => 6, 'null' => false, 'default' => '=', 'comment' => '关联条件'])
            ->addColumn('condition_type', 'string', ['length' => 10, 'null' => false, 'default' => 'and', 'comment' => '条件类型'])
            ->addColumn('status', 'integer', ['length' => 4, 'null' => false, 'default' => 10, 'comment' => '状态：10=开启，20=禁用'])
            ->create();

        $this->table('model_index', ['id' => true,  'comment'=>'系统--模型索引设置表'])
            ->addColumn('model_id', 'integer', ['length' => 11, 'null' => false, 'default' => 0, 'comment' => '模型ID'])
            ->addColumn('model_field_id', 'string', ['length' => 255, 'null' => false, 'default' => '', 'comment' => '模型字段ID, 多个用竖线分隔'])
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
            ->addIndex(['username'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->create();

    }

    public function down()
    {
        $this->table('model')->drop();
        $this->table('admin')->drop();
        $this->table('model')->drop();
        $this->table('admin')->drop();
        $this->table('model')->drop();
        $this->table('admin')->drop();
        $this->table('model')->drop();
        $this->table('admin')->drop();
    }
}
