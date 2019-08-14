<?php
/**
 * Created by www.vuecmf.com
 * User: emei8 <386196596@qq.com>
 * Date: 2019/3/23
 * Time: 23:23
 */

namespace app\api\controller;


use think\facade\Request;

class FieldOption extends Base
{
    protected function initialize(){
        $this->model = model('FieldOption');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\FieldOption();
        }

    }
}