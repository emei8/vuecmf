<?php

namespace app\api\validate;

use think\Validate;

class ModelField extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'model_id'     => 'require',
        'label'       => 'require',
        'field_name'       => 'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'model_id.require'     => '请填写模型ID',
        'label.require'     => '请填写字段中文名称',
        'field_name.require'       => '请填写字段名称',
    ];
}
