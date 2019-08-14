<?php

namespace app\api\validate;

use think\Validate;

class AuthMenu extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title'     => 'require|max:25',
        'pid'       => 'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'     => '请填写菜单名称',
        'title.max'     => '菜单名称长度在 1 到 25 个字符',
        'pid.require'       => '请选择父级菜单',
    ];
}
