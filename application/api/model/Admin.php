<?php
/**
 * Created by www.vuecmf.com
 * User: 386196596@qq.com
 * Date: 2019/04/20
 * Time: 20:25
 */

namespace app\api\model;

class Admin extends Base
{
    protected $auto = ['password'];
    
    protected function setPasswordAttr($value)
    {
        return password_hash($value,PASSWORD_DEFAULT);
    }
    
    
}